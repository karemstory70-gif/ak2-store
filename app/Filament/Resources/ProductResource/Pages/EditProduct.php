<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()->requiresConfirmation()];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (blank($data['slug'] ?? null)) {
            $data['slug'] = $this->record->slug;
        }

        if (($data['is_active'] ?? false) && blank($data['published_at'] ?? null)) {
            $data['published_at'] = now();
        }

        return $data;
    }

    protected function afterSave(): void
    {
        if ($this->record->variants()->exists()) {
            $this->record->update(['stock_quantity' => 0]);
        }
    }
}
