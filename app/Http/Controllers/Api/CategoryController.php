<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /** GET /api/categories — Public */
    public function index()
    {
        $categories = Category::query()->orderBy('name')->get();
        return CategoryResource::collection($categories);
    }

    /** POST /api/categories — Admin */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:categories,name',
            'icon' => 'nullable|string|max:50',
        ]);

        $category = Category::create($validated);
        return new CategoryResource($category);
    }

    /** PUT /api/categories/{id} — Admin */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:categories,name,' . $category->id,
            'icon' => 'nullable|string|max:50',
        ]);

        $category->update($validated);
        return new CategoryResource($category);
    }

    /** DELETE /api/categories/{id} — Admin */
    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json(['message' => 'Kategori dihapus.']);
    }
}
