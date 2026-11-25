@extends('layouts.store')

@section('title', request()->cookie('ak2-language') === 'ar' ? 'AK2 | سلة التسوق' : 'AK2 | Shopping Cart')

@section('content')

<section class="cart-page">

    <div class="container">

        <div class="cart-page-heading">
            <h1 data-i18n="cart-page-title">Shopping Cart</h1>
            <p data-i18n="cart-page-intro">Review your selected products before checkout.</p>
        </div>

        @if (session('success'))
            <div
                class="cart-success-message"
                role="status"
                data-i18n="{{ session('success') === 'Cart quantity updated successfully.' ? 'cart-updated-success' : (session('success') === 'Product removed from your cart successfully.' ? 'cart-removed-success' : '') }}"
            >
                {{ session('success') }}
            </div>
        @endif

        @if ($cart && $cart->items->isNotEmpty())

            @php
                $subtotal = 0;
            @endphp

            <div class="cart-layout">

                {{-- Cart Items --}}

                <div class="cart-items">

                    @foreach ($cart->items as $item)

                        @php
                            $product = $item->product;

                            $variant = $item->variant;

                            $image = $product->images->first();

                            $unitPrice = $variant?->price
                                ?? $product->price;

                            $itemTotal = $unitPrice * $item->quantity;

                            $subtotal += $itemTotal;
                            $productNameArabic = data_get($product->translations, 'ar.name') ?: $product->name;
                        @endphp

                        <article class="cart-item">

                            <div class="cart-item-image">

                                @if ($image)

                                    <img
                                        src="{{ Storage::url($image->image_path) }}"
                                        alt="{{ $image->alt_text ?? $product->name }}"
                                        data-storefront-alt-en="{{ $image->alt_text ?? $product->name }}"
                                        data-storefront-alt-ar="{{ data_get($image->translations, 'ar.alt_text') ?: $productNameArabic }}"
                                    >

                                @else

                                    <span data-i18n="cart-image-placeholder">No Image</span>

                                @endif

                            </div>

                            <div class="cart-item-information">

                                <span class="cart-item-category">
                                    <span data-storefront-content-en="{{ $product->category->name }}" data-storefront-content-ar="{{ data_get($product->category->translations, 'ar.name') ?: $product->category->name }}">{{ $product->category->name }}</span>
                                </span>

                                <h2>
                                    <a href="{{ route('products.show', $product->slug) }}">
                                        <span data-storefront-content-en="{{ $product->name }}" data-storefront-content-ar="{{ $productNameArabic }}">{{ $product->name }}</span>
                                    </a>
                                </h2>

                                @if ($variant)

                                    <div class="cart-item-options">

                                        @if ($variant->size || filled(data_get($variant->translations, 'ar.size')))
                                            <span>
                                                <span data-i18n="size">Size</span>:
                                                <span data-storefront-content-en="{{ $variant->size ?? '' }}" data-storefront-content-ar="{{ data_get($variant->translations, 'ar.size') ?: ($variant->size ?? '') }}">{{ $variant->size }}</span>
                                            </span>
                                        @endif

                                        @if ($variant->color || filled(data_get($variant->translations, 'ar.color')))
                                            <span>
                                                <span data-i18n="color">Color</span>:
                                                <span data-storefront-content-en="{{ $variant->color ?? '' }}" data-storefront-content-ar="{{ data_get($variant->translations, 'ar.color') ?: ($variant->color ?? '') }}">{{ $variant->color }}</span>
                                            </span>
                                        @endif

                                    </div>

                                @endif

                            <div class="cart-item-quantity">
                                <form action="{{ route('cart.update', $item->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')

                                    <label for="quantity-{{ $item->id }}" data-i18n="quantity">Quantity</label>

                                    <input
                                        type="number"
                                        id="quantity-{{ $item->id }}"
                                        name="quantity"
                                        value="{{ old('quantity', $item->quantity) }}"
                                        min="1"
                                        required
                                    >

                                    <button type="submit">
                                        <span data-i18n="update">Update</span>
                                    </button>

                                    @error('quantity')
                                        <p class="form-error">{{ $message }}</p>
                                    @enderror
                                </form>
                            </div>
                            <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                @csrf
                                @method('DELETE')

                                <button type="submit">
                                    <span data-i18n="remove">Remove</span>
                                </button>
                            </form>
                            
                                <p class="cart-item-unit-price">
                                    <span data-i18n="unit-price">Unit Price:</span>
                                    <span dir="ltr">
                                    EGP {{ number_format((float) $unitPrice, 2) }}
                                    </span>
                                </p>

                            </div>

                            <div class="cart-item-total">
                                <span dir="ltr">
                                EGP {{ number_format((float) $itemTotal, 2) }}
                                </span>
                            </div>

                        </article>

                    @endforeach

                </div>

                {{-- Cart Summary --}}

                <aside class="cart-summary">

                    <h2 data-i18n="order-summary">Order Summary</h2>

                    <div class="cart-summary-row">
                        <span data-i18n="subtotal">Subtotal</span>

                        <strong>
                            <span dir="ltr">
                            EGP {{ number_format((float) $subtotal, 2) }}
                            </span>
                        </strong>
                    </div>

                    <div class="cart-summary-row">
                        <span data-i18n="shipping">Shipping</span>
                        <span data-i18n="shipping-at-checkout">Calculated at checkout</span>
                    </div>

                    <div class="cart-summary-total">

                        <span data-i18n="total">Total</span>

                        <strong>
                            <span dir="ltr">
                            EGP {{ number_format((float) $subtotal, 2) }}
                            </span>
                        </strong>

                    </div>

                    <a
                        href="{{ route('checkout') }}"
                        class="cart-checkout-button"
                    >
                        <span data-i18n="proceed-checkout">Proceed to Checkout</span>
                    </a>

                    <a
                        href="{{ route('products.index') }}"
                        class="cart-continue-shopping"
                    >
                        <span data-i18n="continue-shopping">Continue Shopping</span>
                    </a>

                </aside>

            </div>

        @else

            {{-- Empty Cart --}}

            <div class="cart-empty">

                <h2 data-i18n="cart-empty-title">Your Cart Is Empty</h2>

                <p data-i18n="cart-empty-description">
                    You haven't added any products to your cart yet.
                </p>

                <a href="{{ route('products.index') }}" data-i18n="explore-products">
                    Explore Products
                </a>

            </div>

        @endif

    </div>

</section>

@endsection