<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\PriceHistory;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /** GET /api/products — Public */
    public function index(Request $request)
    {
        $query = Product::with('category')->withCount('favorites');

        // Search
        if ($search = $request->search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
                ->orWhere('barcode', $search);
        }

        // Filter by category
        if ($categoryId = $request->category_id) {
            $query->where('category_id', $categoryId);
        }

        // Filter by promo
        if ($request->boolean('promo')) {
            $query->where('is_promo', true);
        }

        // Best sellers
        if ($request->boolean('best_seller')) {
            $query->where('is_best_seller', true);
        }

        // Low stock (admin only)
        if ($request->boolean('low_stock')) {
            $query->where('stock', '<=', 5);
        }

        $products = $query->orderBy('name')->paginate($request->input('per_page', 20));

        return ProductResource::collection($products);
    }

    /** GET /api/products/{id} — Public */
    public function show(Product $product)
    {
        $product->load('category');
        return new ProductResource($product);
    }

    /** POST /api/products — Admin */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'is_promo' => 'boolean',
            'promo_price' => 'nullable|numeric|min:0',
            'barcode' => 'nullable|string|unique:products,barcode',
            'is_best_seller' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product = Product::create($validated);

        return new ProductResource($product->load('category'));
    }

    /** PUT /api/products/{id} — Admin */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => 'sometimes|exists:categories,id',
            'name' => 'sometimes|string|max:255',
            'price' => 'sometimes|numeric|min:0',
            'stock' => 'sometimes|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'is_promo' => 'boolean',
            'promo_price' => 'nullable|numeric|min:0',
            'barcode' => 'nullable|string|unique:products,barcode,' . $product->id,
            'is_best_seller' => 'boolean',
        ]);

        // Track price history if price changed
        if (isset($validated['price']) && $validated['price'] != $product->price) {
            PriceHistory::create([
                'product_id' => $product->id,
                'changed_by' => $request->user()->id,
                'old_price' => $product->price,
                'new_price' => $validated['price'],
                'note' => $request->input('price_note'),
                'changed_at' => now(),
            ]);
        }

        if ($request->hasFile('image')) {
            // Delete old image
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);

        return new ProductResource($product->load('category'));
    }

    /** DELETE /api/products/{id} — Admin */
    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();

        return response()->json(['message' => 'Barang dihapus.']);
    }

    /** GET /api/products/barcode/{barcode} — Public */
    public function findByBarcode(string $barcode)
    {
        $product = Product::with('category')->where('barcode', $barcode)->firstOrFail();
        return new ProductResource($product);
    }
}
