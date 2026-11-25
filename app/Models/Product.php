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
        'slug',
        'sku',
        'description',
        'price',
        'stock_quantity',
        'compare_at_price',
        'is_active',
        'is_featured',
        'published_at',
        'translations',
    ];

    protected function casts(): array
    {
        return [
            'translations' => 'array',
            'price' => 'decimal:2',
            'stock_quantity' => 'integer',
            'compare_at_price' => 'decimal:2',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    /**
     * Get the category that owns this product.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get all variants of this product.
     */
    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    /**
     * Get all images of this product.
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }
}
