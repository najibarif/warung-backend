<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'price' => $this->price,
            'effective_price' => $this->effective_price,
            'stock' => $this->stock,
            'is_low_stock' => $this->is_low_stock,
            'description' => $this->description,
            'image' => $this->image ? asset('storage/' . $this->image) : null,
            'is_promo' => $this->is_promo,
            'promo_price' => $this->promo_price,
            'is_best_seller' => $this->is_best_seller,
            'barcode' => $this->barcode,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
