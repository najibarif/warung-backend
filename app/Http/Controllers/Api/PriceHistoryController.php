<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PriceHistoryResource;
use App\Models\Product;

class PriceHistoryController extends Controller
{
    /** GET /api/products/{product}/price-history — Auth */
    public function index(Product $product)
    {
        $histories = $product->priceHistories()
            ->with('changedBy:id,name')
            ->latest('changed_at')
            ->get();

        return PriceHistoryResource::collection($histories);
    }
}
