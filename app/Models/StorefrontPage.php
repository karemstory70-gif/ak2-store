<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StorefrontPage extends Model
{
    protected $fillable = [
        'slug',
        'title',
        'meta_title',
        'meta_description',
        'eyebrow',
        'heading',
        'subheading',
        'body',
        'hero_image_path',
        'primary_cta_text',
        'primary_cta_url',
        'sections',
        'is_published',
        'translations',
    ];

    protected function casts(): array
    {
        return [
            'sections' => 'array',
            'is_published' => 'boolean',
            'translations' => 'array',
        ];
    }
}
