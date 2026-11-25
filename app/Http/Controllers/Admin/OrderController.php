<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $orders = Order::query()
            ->withCount('items')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->input('search');

                $query->where(function ($q) use ($search) {
                    $q->where('order_number', 'like', "%{$search}%")
                        ->orWhere('customer_name', 'like', "%{$search}%")
                        ->orWhere('customer_phone', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->input('status'));
            })
            ->latest('placed_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        $order->load('items');

        return view('admin.orders.show', compact('order'));
    }

    public function update(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'status' => [
                'required',
                Rule::in(['pending', 'processing', 'shipped', 'delivered', 'cancelled']),
            ],
            'payment_status' => [
                'required',
                Rule::in(['pending', 'paid', 'failed', 'refunded']),
            ],
            'admin_notes' => ['nullable', 'string', 'max:5000'],
        ]);

        DB::transaction(function () use ($order, $validated) {
            $order = Order::query()
                ->whereKey($order->id)
                ->lockForUpdate()
                ->firstOrFail();

            // A cancelled order cannot be reopened.
            if ($order->status === 'cancelled' && $validated['status'] !== 'cancelled') {
                abort(422, 'A cancelled order cannot be reopened.');
            }

            // Restore inventory exactly once when an order is cancelled.
            if (
                $order->status !== 'cancelled' &&
                $validated['status'] === 'cancelled'
            ) {
                $order->load('items');

                foreach ($order->items as $item) {
                    if ($item->product_variant_id) {
                        $variant = \App\Models\ProductVariant::query()
                            ->whereKey($item->product_variant_id)
                            ->lockForUpdate()
                            ->first();

                        if ($variant) {
                            $variant->increment('stock_quantity', $item->quantity);
                        }
                    } elseif ($item->product_id) {
                        $product = \App\Models\Product::query()
                            ->whereKey($item->product_id)
                            ->lockForUpdate()
                            ->first();

                        if ($product) {
                            $product->increment('stock_quantity', $item->quantity);
                        }
                    }
                }
            }

            $order->update([
                'status' => $validated['status'],
                'payment_status' => $validated['payment_status'],
                'admin_notes' => $validated['admin_notes'] ?? null,
            ]);
        });

        return back()->with('success', 'Order updated successfully.');
    }
}