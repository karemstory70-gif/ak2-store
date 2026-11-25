<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class LatestOrdersWidget extends TableWidget
{
    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        $user = Auth::user();

        if (! Schema::hasTable('orders')) {
            return false;
        }

        return $user?->isProjectOwner()
            || $user?->hasShieldPermission('View:LatestOrdersWidget')
            || false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Latest orders')
            ->query(Order::query()->latest('placed_at')->limit(8))
            ->columns([
                TextColumn::make('order_number')->searchable(),
                TextColumn::make('customer_name')->searchable(),
                TextColumn::make('status')->badge(),
                TextColumn::make('payment_status')->badge(),
                TextColumn::make('total')->money('EGP'),
                TextColumn::make('placed_at')->dateTime(),
            ])
            ->recordUrl(fn (Order $record): string => OrderResource::getUrl('view', ['record' => $record]));
    }
}
