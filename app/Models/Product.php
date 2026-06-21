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
        'description',
        'image',
        'is_promo',
        'promo_price',
        'barcode',
        'is_best_seller',
    ];

    protected $casts = [
        'is_promo' => 'boolean',
        'is_best_seller' => 'boolean',
        'price' => 'float',
        'promo_price' => 'float',
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

    /** Effective price: promo_price if on promo, else price */
    public function getEffectivePriceAttribute(): float
    {
        return ($this->is_promo && $this->promo_price) ? $this->promo_price : $this->price;
    }

    public function getIsLowStockAttribute(): bool
    {
        return $this->stock <= 5;
    }
}
