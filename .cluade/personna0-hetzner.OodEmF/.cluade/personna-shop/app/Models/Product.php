<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'price', 'stock', 'sizes', 'image', 'is_active',
    ];

    protected $casts = [
        'sizes'     => 'array',
        'is_active' => 'boolean',
        'price'     => 'decimal:2',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
