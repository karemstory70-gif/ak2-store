<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'image_path',
        'mobile_image_path',
        'alt_text',
        'button_text',
        'button_url',
        'placement',
        'is_active',
        'sort_order',
        'starts_at',
        'ends_at',
        'translations',
    ];

    protected function casts(): array
    {
        return [
            'translations' => 'array',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
        ];
    }
}
