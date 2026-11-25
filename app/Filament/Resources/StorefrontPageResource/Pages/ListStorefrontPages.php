<?php

namespace App\Filament\Resources\StorefrontPageResource\Pages;

use App\Filament\Resources\StorefrontPageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStorefrontPages extends ListRecords
{
    protected static string $resource = StorefrontPageResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
