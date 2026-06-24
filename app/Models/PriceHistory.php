<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PriceHistory extends Model
{
    public $timestamps = false;

    protected $fillable = ['product_id', 'changed_by', 'old_price', 'new_price', 'note', 'changed_at'];

    protected $casts = [
        'old_price' => 'float',
        'new_price' => 'float',
        'changed_at' => 'datetime',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
