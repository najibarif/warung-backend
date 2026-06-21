<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = ['name', 'icon'];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
