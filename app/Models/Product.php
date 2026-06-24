<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'price',
        'stock',
        'image',
        'barcode',
        'description',
        'is_promo',
        'promo_price',
        'is_best_seller',
    ];

    protected $casts = [
        'price' => 'float',
        'stock' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function priceHistories(): HasMany
    {
        return $this->hasMany(PriceHistory::class);
    }

    public function getEffectivePriceAttribute(): float
    {
        return $this->price;
    }

    public function getIsLowStockAttribute(): bool
    {
        return $this->stock <= 5;
    }
}
