<?php

namespace App\Filament\Resources\StorefrontPageResource\Pages;

use App\Filament\Resources\StorefrontPageResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditStorefrontPage extends EditRecord
{
    protected static string $resource = StorefrontPageResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()->requiresConfirmation()];
    }
}
