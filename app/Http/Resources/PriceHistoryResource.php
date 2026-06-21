<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PriceHistoryResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'old_price' => $this->old_price,
            'new_price' => $this->new_price,
            'note' => $this->note,
            'changed_by' => $this->changedBy?->name,
            'changed_at' => $this->changed_at,
        ];
    }
}
