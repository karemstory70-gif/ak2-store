<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use App\Models\Category;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (blank($data['slug'] ?? null)) {
            $baseSlug = Str::slug($data['name']) ?: 'category';
            $slug = $baseSlug;
            $counter = 1;

            while (Category::query()->where('slug', $slug)->exists()) {
                $slug = $baseSlug.'-'.$counter++;
            }

            $data['slug'] = $slug;
        }

        return $data;
    }
}
