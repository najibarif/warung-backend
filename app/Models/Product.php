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
        'expired_at',
        'expiration_discount',
    ];

    protected $casts = [
        'price' => 'float',
        'stock' => 'integer',
        'is_promo' => 'boolean',
        'is_best_seller' => 'boolean',
        'expired_at' => 'date',
        'expiration_discount' => 'integer',
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

    public function getIsPromoAttribute($value): bool
    {
        if ($value) {
            return true;
        }

        if ($this->expired_at) {
            $daysLeft = now()->diffInDays(\Carbon\Carbon::parse($this->expired_at), false);
            if ($daysLeft >= 0 && $daysLeft <= 30) {
                return true;
            }
        }

        return false;
    }

    public function getPromoPriceAttribute($value): ?float
    {
        if ($value !== null && $value > 0) {
            return (float) $value;
        }

        if ($this->expired_at) {
            $daysLeft = now()->diffInDays(\Carbon\Carbon::parse($this->expired_at), false);
            if ($daysLeft >= 0 && $daysLeft <= 30) {
                $discount = $this->expiration_discount ?? 20;
                return round($this->price * ((100 - $discount) / 100), 2);
            }
        }

        return null;
    }

    public function getEffectivePriceAttribute(): float
    {
        if ($this->is_promo && $this->promo_price !== null) {
            return $this->promo_price;
        }
        return $this->price;
    }

    public function getIsLowStockAttribute(): bool
    {
        return $this->stock <= 5;
    }
}
