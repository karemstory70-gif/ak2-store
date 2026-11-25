<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'orders' => Order::count(),

            'pending_orders' => Order::where('status', 'pending')->count(),

            'revenue' => Order::where('payment_status', 'paid')
                ->sum('total'),

            'customers' => User::where('role', 'customer')->count(),

            'products' => Product::where('is_active', true)->count(),
        ];

        $latestOrders = Order::withCount('items')
            ->latest('placed_at')
            ->take(8)
            ->get();

        return view('admin.dashboard', compact('stats', 'latestOrders'));
    }
}