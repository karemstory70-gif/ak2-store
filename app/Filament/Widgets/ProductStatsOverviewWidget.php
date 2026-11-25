<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class ProductStatsOverviewWidget extends StatsOverviewWidget
{
    public static function canView(): bool
    {
        $user = Auth::user();

        if (! Schema::hasTable('products')) {
            return false;
        }

        return $user?->isProjectOwner()
            || $user?->hasShieldPermission('View:ProductStatsOverviewWidget')
            || false;
    }

    protected function getStats(): array
    {
        $lowStock = Product::query()->where(function ($query): void {
            $query->where(function ($query): void {
                $query->whereDoesntHave('variants')
                    ->where('stock_quantity', '<=', 5);
            })->orWhereHas('variants', function ($query): void {
                $query->where('is_active', true)
                    ->where('stock_quantity', '<=', 5);
            });
        })->count();

        return [
            Stat::make('Total products', Product::query()->count()),
            Stat::make('Active products', Product::query()->where('is_active', true)->count()),
            Stat::make('Inactive products', Product::query()->where('is_active', false)->count()),
            Stat::make('Low stock', $lowStock),
        ];
    }
}
