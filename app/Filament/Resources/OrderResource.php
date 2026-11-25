<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages\EditOrder;
use App\Filament\Resources\OrderResource\Pages\ListOrders;
use App\Filament\Resources\OrderResource\Pages\ViewOrder;
use App\Filament\Resources\OrderResource\RelationManagers\ItemsRelationManager;
use App\Models\Order;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Order')->columns(2)->schema([
                TextInput::make('order_number')->disabled()->dehydrated(false),
                TextInput::make('customer_name')->disabled()->dehydrated(false),
                TextInput::make('customer_email')->disabled()->dehydrated(false),
                TextInput::make('customer_phone')->disabled()->dehydrated(false),
                TextInput::make('shipping_address')->disabled()->dehydrated(false)->columnSpanFull(),
                TextInput::make('shipping_city')->disabled()->dehydrated(false),
                TextInput::make('shipping_state')->disabled()->dehydrated(false),
                TextInput::make('shipping_postal_code')->disabled()->dehydrated(false),
                TextInput::make('shipping_country')->disabled()->dehydrated(false),
                TextInput::make('subtotal')->disabled()->dehydrated(false)->prefix('EGP'),
                TextInput::make('shipping_amount')->disabled()->dehydrated(false)->prefix('EGP'),
                TextInput::make('tax_amount')->disabled()->dehydrated(false)->prefix('EGP'),
                TextInput::make('discount_amount')->disabled()->dehydrated(false)->prefix('EGP'),
                TextInput::make('total')->disabled()->dehydrated(false)->prefix('EGP'),
                TextInput::make('payment_method')->disabled()->dehydrated(false),
                Select::make('status')->options([
                    'pending' => 'Pending',
                    'processing' => 'Processing',
                    'shipped' => 'Shipped',
                    'delivered' => 'Delivered',
                    'cancelled' => 'Cancelled',
                ])->required(),
                Select::make('payment_status')->options([
                    'pending' => 'Pending',
                    'paid' => 'Paid',
                    'failed' => 'Failed',
                    'refunded' => 'Refunded',
                ])->required(),
                Textarea::make('customer_notes')->disabled()->dehydrated(false)->columnSpanFull(),
                Textarea::make('admin_notes')->maxLength(5000)->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_number')->searchable()->sortable(),
                TextColumn::make('customer_name')->searchable(),
                TextColumn::make('customer_phone')->searchable(),
                TextColumn::make('status')->badge(),
                TextColumn::make('payment_status')->badge(),
                TextColumn::make('total')->money('EGP')->sortable(),
                TextColumn::make('placed_at')->dateTime()->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    'pending' => 'Pending',
                    'processing' => 'Processing',
                    'shipped' => 'Shipped',
                    'delivered' => 'Delivered',
                    'cancelled' => 'Cancelled',
                ]),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->defaultSort('placed_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [ItemsRelationManager::class];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOrders::route('/'),
            'view' => ViewOrder::route('/{record}'),
            'edit' => EditOrder::route('/{record}/edit'),
        ];
    }
}
