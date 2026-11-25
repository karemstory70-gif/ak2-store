<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use App\Models\Category;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCategory extends EditRecord
{
    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->requiresConfirmation()
                ->disabled(fn (Category $record): bool => $record->products()->exists() || $record->children()->exists()),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (blank($data['slug'] ?? null)) {
            $data['slug'] = $this->record->slug;
        }

        return $data;
    }
}
