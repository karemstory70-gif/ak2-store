<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    /**
     * Display the checkout page.
     */
    public function index(Request $request): View|RedirectResponse
    {
        $cart = $request->user()
            ->cart()
            ->with([
                'items.product.images',
                'items.variant',
            ])
            ->first();

        if ((!$cart || $cart->items->isEmpty()) && !session('order_number')) {
            return redirect()
                ->route('cart')
                ->with('error', 'Your cart is empty.');
        }

        return view('pages.checkout', compact('cart'));
    }

    /**
     * Validate the cart and place the order.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['nullable', 'email', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:30'],
            'shipping_address' => ['required', 'string', 'max:255'],
            'shipping_city' => ['required', 'string', 'max:100'],
            'shipping_state' => ['nullable', 'string', 'max:100'],
            'shipping_postal_code' => ['nullable', 'string', 'max:30'],
            'customer_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $order = DB::transaction(function () use ($request, $validated) {

            $cart = Cart::query()
                ->where('user_id', $request->user()->id)
                ->lockForUpdate()
                ->first();

            if (!$cart) {
                throw ValidationException::withMessages([
                    'cart' => 'Your cart is empty.',
                ]);
            }

            $items = $cart->items()
                ->with(['product.images', 'variant'])
                ->lockForUpdate()
                ->get();

            if ($items->isEmpty()) {
                throw ValidationException::withMessages([
                    'cart' => 'Your cart is empty.',
                ]);
            }

            $orderLines = [];
            $subtotal = 0;

            foreach ($items as $item) {

                $product = Product::query()
                    ->whereKey($item->product_id)
                    ->lockForUpdate()
                    ->first();

                if (
                    !$product ||
                    !$product->is_active ||
                    !$product->published_at ||
                    $product->published_at->isFuture()
                ) {
                    throw ValidationException::withMessages([
                        'cart' => 'One of the products is no longer available.',
                    ]);
                }

                $hasVariants = $product->variants()->exists();
                $variant = null;

                if ($hasVariants) {

                    if (!$item->product_variant_id) {
                        throw ValidationException::withMessages([
                            'cart' => "Please select an option for {$product->name}.",
                        ]);
                    }

                    $variant = ProductVariant::query()
                        ->whereKey($item->product_variant_id)
                        ->where('product_id', $product->id)
                        ->where('is_active', true)
                        ->lockForUpdate()
                        ->first();

                    if (!$variant) {
                        throw ValidationException::withMessages([
                            'cart' => "An option for {$product->name} is unavailable.",
                        ]);
                    }

                    $stock = $variant->stock_quantity;
                    $price = $variant->price ?? $product->price;

                    $variantDetails = collect([
                        $variant->size,
                        $variant->color,
                    ])->filter()->implode(' / ');

                } else {

                    if ($item->product_variant_id) {
                        throw ValidationException::withMessages([
                            'cart' => "The selected option for {$product->name} is invalid.",
                        ]);
                    }

                    $stock = $product->stock_quantity;
                    $price = $product->price;
                    $variantDetails = null;
                }

                if ($item->quantity > $stock) {
                    throw ValidationException::withMessages([
                        'cart' => "Only {$stock} units of {$product->name} are available.",
                    ]);
                }

                $lineTotal = round((float) $price * $item->quantity, 2);
                $subtotal += $lineTotal;

                $image = $product->images
                    ->firstWhere('is_primary', true)
                    ?? $product->images->first();

                $orderLines[] = [
                    'product' => $product,
                    'variant' => $variant,
                    'product_name' => $product->name,
                    'product_sku' => $variant?->sku ?? $product->sku,
                    'product_image' => $image?->image_path,
                    'variant_details' => $variantDetails,
                    'unit_price' => $price,
                    'quantity' => $item->quantity,
                    'total' => $lineTotal,
                ];
            }

            $subtotal = round($subtotal, 2);

            // Shipping and taxes are currently zero.
            // They can be configured later in the admin settings.
            $shipping = 0;
            $discount = 0;
            $tax = 0;
            $total = round($subtotal + $shipping + $tax - $discount, 2);

            $order = Order::create([
                'order_number' => 'AK2-' . now()->format('Ymd') . '-' . Str::upper(Str::random(6)),
                'user_id' => $request->user()->id,
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'] ?? null,
                'customer_phone' => $validated['customer_phone'],
                'shipping_address' => $validated['shipping_address'],
                'shipping_city' => $validated['shipping_city'],
                'shipping_state' => $validated['shipping_state'] ?? null,
                'shipping_postal_code' => $validated['shipping_postal_code'] ?? null,
                'shipping_country' => 'Egypt',
                'subtotal' => $subtotal,
                'discount_amount' => $discount,
                'shipping_amount' => $shipping,
                'tax_amount' => $tax,
                'total' => $total,
                'currency' => 'EGP',
                'status' => 'pending',
                'payment_method' => 'cash_on_delivery',
                'payment_status' => 'pending',
                'customer_notes' => $validated['customer_notes'] ?? null,
                'placed_at' => now(),
            ]);

            foreach ($orderLines as $line) {

                $order->items()->create([
                    'product_id' => $line['product']->id,
                    'product_variant_id' => $line['variant']?->id,
                    'product_name' => $line['product_name'],
                    'product_sku' => $line['product_sku'],
                    'product_image' => $line['product_image'],
                    'variant_details' => $line['variant_details'],
                    'unit_price' => $line['unit_price'],
                    'quantity' => $line['quantity'],
                    'total' => $line['total'],
                ]);

                // Decrease the correct inventory.
                if ($line['variant']) {
                    $line['variant']->decrement('stock_quantity', $line['quantity']);
                } else {
                    $line['product']->decrement('stock_quantity', $line['quantity']);
                }
            }

            // Empty the cart only after the order has been created.
            $cart->items()->delete();

            return $order;
        });

        return redirect()
            ->route('checkout')
            ->with('order_number', $order->order_number)
            ->with('success', 'Your order has been placed successfully.');
    }
}