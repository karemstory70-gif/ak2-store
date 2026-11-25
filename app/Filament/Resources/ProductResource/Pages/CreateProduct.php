<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Models\Product;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (blank($data['slug'] ?? null)) {
            $baseSlug = Str::slug($data['name']) ?: 'product';
            $slug = $baseSlug;
            $counter = 1;

            while (Product::query()->where('slug', $slug)->exists()) {
                $slug = $baseSlug.'-'.$counter++;
            }

            $data['slug'] = $slug;
        }

        if (($data['is_active'] ?? false) && blank($data['published_at'] ?? null)) {
            $data['published_at'] = now();
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        if ($this->record->variants()->exists()) {
            $this->record->update(['stock_quantity' => 0]);
        }
    }
}
