<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    public function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('product_name'),
            TextColumn::make('product_sku'),
            TextColumn::make('variant_details'),
            TextColumn::make('unit_price')->money('EGP'),
            TextColumn::make('quantity')->numeric(),
            TextColumn::make('total')->money('EGP'),
        ]);
    }
}
