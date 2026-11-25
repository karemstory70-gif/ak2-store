<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CartController extends Controller
{
    /**
     * Display the authenticated user's shopping cart.
     */
    public function index(Request $request): View
    {
        $cart = $request->user()
            ->cart()
            ->with([
                'items.product.images' => function ($query) {
                    $query->orderByDesc('is_primary')
                        ->orderBy('sort_order');
                },
                'items.variant',
            ])
            ->first();

        return view('pages.cart', compact('cart'));
    }
    /**
 * Add a product to the authenticated user's cart.
 */
public function add(Request $request): RedirectResponse
{
    $validated = $request->validate([
        'product_id' => ['required', 'integer', 'exists:products,id'],
        'variant_id' => ['nullable', 'integer', 'exists:product_variants,id'],
        'quantity' => ['required', 'integer', 'min:1'],
    ]);

    DB::transaction(function () use ($request, $validated) {

        // Find the product and lock its record.
        $product = Product::query()
            ->whereKey($validated['product_id'])
            ->lockForUpdate()
            ->firstOrFail();

        // Only active and published products can be purchased.
        if (
            !$product->is_active ||
            !$product->published_at ||
            $product->published_at->isFuture()
        ) {
            throw ValidationException::withMessages([
                'product_id' => 'This product is currently unavailable.',
            ]);
        }

        // Check whether the product has variants.
        $hasVariants = ProductVariant::query()
            ->where('product_id', $product->id)
            ->exists();

        $variant = null;

        if ($hasVariants) {

            if (empty($validated['variant_id'])) {
                throw ValidationException::withMessages([
                    'variant_id' => 'Please select a size or color.',
                ]);
            }

            $variant = ProductVariant::query()
                ->whereKey($validated['variant_id'])
                ->where('product_id', $product->id)
                ->where('is_active', true)
                ->lockForUpdate()
                ->first();

            if (!$variant) {
                throw ValidationException::withMessages([
                    'variant_id' => 'The selected option is unavailable.',
                ]);
            }

            $stock = $variant->stock_quantity;

        } else {

            if (!empty($validated['variant_id'])) {
                throw ValidationException::withMessages([
                    'variant_id' => 'This product does not have options.',
                ]);
            }

            $stock = $product->stock_quantity;
        }

        // Get or create the user's cart.
        $cart = Cart::query()->firstOrCreate([
            'user_id' => $request->user()->id,
        ]);

        // Find an existing matching cart item.
        $itemQuery = $cart->items()
            ->where('product_id', $product->id);

        if ($variant) {
            $itemQuery->where('product_variant_id', $variant->id);
        } else {
            $itemQuery->whereNull('product_variant_id');
        }

        $item = $itemQuery->lockForUpdate()->first();

        $newQuantity = ($item?->quantity ?? 0)
            + $validated['quantity'];

        // Check available stock.
        if ($newQuantity > $stock) {
            throw ValidationException::withMessages([
                'quantity' => "Only {$stock} items are currently available.",
            ]);
        }

        if ($item) {
            $item->update([
                'quantity' => $newQuantity,
            ]);
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'product_variant_id' => $variant?->id,
                'quantity' => $validated['quantity'],
            ]);
        }
    });

        return redirect()
            ->back()
            ->with('success', 'Product added to your cart successfully.');
}
public function update(Request $request, int $itemId): RedirectResponse
{
    $validated = $request->validate([
        'quantity' => ['required', 'integer', 'min:1'],
    ]);

    DB::transaction(function () use ($request, $itemId, $validated) {

        // التأكد أن العنصر موجود داخل سلة المستخدم الحالي
        $cart = $request->user()->cart()->firstOrFail();

        $itemSnapshot = $cart->items()
            ->whereKey($itemId)
            ->firstOrFail();

        // قفل المنتج أثناء فحص المخزون
        $product = Product::query()
            ->whereKey($itemSnapshot->product_id)
            ->lockForUpdate()
            ->firstOrFail();

        if (
            !$product->is_active ||
            !$product->published_at ||
            $product->published_at->isFuture()
        ) {
            throw ValidationException::withMessages([
                'quantity' => 'This product is currently unavailable.',
            ]);
        }

        // لو العنصر مرتبط بمقاس أو لون، نفحص مخزون الاختيار نفسه
        if ($itemSnapshot->product_variant_id !== null) {
            $variant = ProductVariant::query()
                ->whereKey($itemSnapshot->product_variant_id)
                ->where('product_id', $product->id)
                ->where('is_active', true)
                ->lockForUpdate()
                ->first();

            if (!$variant) {
                throw ValidationException::withMessages([
                    'quantity' => 'This product option is unavailable.',
                ]);
            }

            $stock = $variant->stock_quantity;
        } else {
            $stock = $product->stock_quantity;
        }

        // قفل عنصر السلة قبل تحديثه
        $item = $cart->items()
            ->whereKey($itemId)
            ->lockForUpdate()
            ->firstOrFail();

        if ($item->product_id !== $product->id) {
            throw ValidationException::withMessages([
                'quantity' => 'The cart item is invalid.',
            ]);
        }

        if ($validated['quantity'] > $stock) {
            throw ValidationException::withMessages([
                'quantity' => "Only {$stock} items are currently available.",
            ]);
        }

        $item->update([
            'quantity' => $validated['quantity'],
        ]);
    });

    return redirect()
        ->route('cart')
        ->with('success', 'Cart quantity updated successfully.');
}
public function remove(Request $request, int $itemId): RedirectResponse
{
    $cart = $request->user()->cart()->firstOrFail();

    $item = $cart->items()
        ->whereKey($itemId)
        ->firstOrFail();

    $item->delete();

    return redirect()
        ->route('cart')
        ->with('success', 'Product removed from your cart successfully.');
}
}