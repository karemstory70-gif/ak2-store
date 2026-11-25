<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class StoreStatsOverviewWidget extends StatsOverviewWidget
{
    public static function canView(): bool
    {
        $user = Auth::user();

        if (! Schema::hasTable('orders') || ! Schema::hasTable('products') || ! Schema::hasTable('users')) {
            return false;
        }

        return $user?->isProjectOwner()
            || $user?->hasShieldPermission('View:StoreStatsOverviewWidget')
            || false;
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Orders', Order::query()->count()),
            Stat::make('Pending orders', Order::query()->where('status', 'pending')->count()),
            Stat::make('Paid revenue', number_format((float) Order::query()->where('payment_status', 'paid')->sum('total'), 2).' EGP'),
            Stat::make('Customers', User::query()->where('role', 'customer')->count()),
            Stat::make('Active products', Product::query()->where('is_active', true)->count()),
        ];
    }
}
