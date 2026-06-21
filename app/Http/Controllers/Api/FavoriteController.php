<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FavoriteResource;
use App\Models\Favorite;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    /** GET /api/favorites — Auth */
    public function index(Request $request)
    {
        $favorites = $request->user()
            ->favorites()
            ->with('product.category')
            ->get();

        return FavoriteResource::collection($favorites);
    }

    /** POST /api/favorites — Auth */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $favorite = Favorite::firstOrCreate([
            'user_id' => $request->user()->id,
            'product_id' => $request->product_id,
        ]);

        return new FavoriteResource($favorite->load('product.category'));
    }

    /** DELETE /api/favorites/{product_id} — Auth (by product_id) */
    public function destroy(Request $request, int $productId)
    {
        $request->user()
            ->favorites()
            ->where('product_id', $productId)
            ->delete();

        return response()->json(['message' => 'Favorit dihapus.']);
    }
}
