<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductImage extends Model
{
    protected $fillable = [
        'product_id',
        'image_path',
        'alt_text',
        'is_primary',
        'sort_order',
        'translations',
    ];

    protected function casts(): array
    {
        return [
            'is_primary' => 'boolean',
            'sort_order' => 'integer',
            'translations' => 'array',
        ];
    }

    /**
     * Get the product that owns this image.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
