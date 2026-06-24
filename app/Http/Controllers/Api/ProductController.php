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
        /** @var \Illuminate\Database\Eloquent\Builder $query */
        $query = Product::query()->with('category');

        // Search
        if ($search = $request->search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('barcode', $search);
        }

        // Filter by category
        if ($categoryId = $request->category_id) {
            $query->where('category_id', $categoryId);
        }

        // Filter
        if ($request->filter === 'low_stock') {
            $query->where('stock', '<=', 10)->where('stock', '>', 0);
        } elseif ($request->filter === 'out_of_stock') {
            $query->where('stock', '<=', 0);
        } elseif ($request->boolean('low_stock')) {
            $query->where('stock', '<=', 10);
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
            'category_id' => 'required|string',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable',
            'barcode' => 'nullable|string|unique:products,barcode',
            'description' => 'nullable|string',
            'is_promo' => 'nullable|boolean',
            'promo_price' => 'nullable|numeric|min:0',
            'is_best_seller' => 'nullable|boolean',
            'expired_at' => 'nullable|date_format:Y-m-d',
            'expiration_discount' => 'nullable|integer|min:0|max:100',
        ]);

        $validated['price'] = (float) $validated['price'];
        $validated['stock'] = (int) $validated['stock'];
        if (isset($validated['is_promo'])) $validated['is_promo'] = filter_var($validated['is_promo'], FILTER_VALIDATE_BOOLEAN);
        if (isset($validated['is_best_seller'])) $validated['is_best_seller'] = filter_var($validated['is_best_seller'], FILTER_VALIDATE_BOOLEAN);
        if (isset($validated['expiration_discount'])) $validated['expiration_discount'] = (int) $validated['expiration_discount'];
        if (isset($validated['promo_price'])) {
            $validated['promo_price'] = ($validated['promo_price'] !== '' && $validated['promo_price'] !== null) ? (float) $validated['promo_price'] : null;
        }

        if ($request->hasFile('image')) {
            try {
                $path = $request->file('image')->store('products', 'public');
                $validated['image'] = $path;
            } catch (\Exception $e) {
                // Serverless: file upload may not persist, skip silently
            }
        }

        $product = Product::create($validated);

        return new ProductResource($product->load('category'));
    }

    /** PUT /api/products/{id} — Admin */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => 'sometimes|string',
            'name' => 'sometimes|string|max:255',
            'price' => 'sometimes|numeric|min:0',
            'stock' => 'sometimes|integer|min:0',
            'image' => 'nullable',
            'barcode' => 'nullable|string|unique:products,barcode,' . $product->id,
            'description' => 'nullable|string',
            'is_promo' => 'nullable|boolean',
            'promo_price' => 'nullable|numeric|min:0',
            'is_best_seller' => 'nullable|boolean',
            'expired_at' => 'nullable|date_format:Y-m-d',
            'expiration_discount' => 'nullable|integer|min:0|max:100',
        ]);

        if (isset($validated['price'])) $validated['price'] = (float) $validated['price'];
        if (isset($validated['stock'])) $validated['stock'] = (int) $validated['stock'];
        if (isset($validated['is_promo'])) $validated['is_promo'] = filter_var($validated['is_promo'], FILTER_VALIDATE_BOOLEAN);
        if (isset($validated['is_best_seller'])) $validated['is_best_seller'] = filter_var($validated['is_best_seller'], FILTER_VALIDATE_BOOLEAN);
        if (isset($validated['expiration_discount'])) $validated['expiration_discount'] = (int) $validated['expiration_discount'];
        if (isset($validated['promo_price'])) {
            $validated['promo_price'] = ($validated['promo_price'] !== '' && $validated['promo_price'] !== null) ? (float) $validated['promo_price'] : null;
        }

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
            try {
                if ($product->image && !str_starts_with($product->image, 'http')) {
                    Storage::disk('public')->delete($product->image);
                }
                $validated['image'] = $request->file('image')->store('products', 'public');
            } catch (\Exception $e) {
                // Serverless: file upload may not persist, skip silently
            }
        }

        $product->update($validated);

        return new ProductResource($product->load('category'));
    }

    /** DELETE /api/products/{id} — Admin */
    public function destroy(Product $product)
    {
        if ($product->image && !str_starts_with($product->image, 'http')) {
            try {
                Storage::disk('public')->delete($product->image);
            } catch (\Exception $e) {}
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
