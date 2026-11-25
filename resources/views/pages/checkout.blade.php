@extends('layouts.store')

@section('title', request()->cookie('ak2-language') === 'ar' ? 'إتمام الطلب | AK2' : 'Checkout | AK2')

@section('content')

<section class="checkout-page">
    <div class="container checkout-container">

        <div class="checkout-heading">
            <div>
                <p class="checkout-eyebrow" data-i18n="checkout-eyebrow">AK2 secure checkout</p>
                <h1 data-i18n="checkout-title">Checkout</h1>
            </div>
            <ol aria-label="Checkout progress" data-i18n-aria="checkout-progress" class="checkout-progress">
                <li class="is-current"><span>1.</span> <span data-i18n="delivery">Delivery</span></li>
                <li aria-hidden="true" class="checkout-progress-separator">›</li>
                <li><span>2.</span> <span data-i18n="review">Review</span></li>
                <li aria-hidden="true" class="checkout-progress-separator">›</li>
                <li><span>3.</span> <span data-i18n="confirmation">Confirmation</span></li>
            </ol>
        </div>

        @if(session('success') && session('order_number'))
            <div
                class="checkout-success"
                role="status"
                aria-labelledby="checkout-success-title"
            >
                <span class="checkout-success-mark" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" focusable="false">
                        <path d="m5 12.5 4.5 4.5L19 7" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
                <div class="checkout-success-content">
                    <h2 id="checkout-success-title" data-i18n="order-success-title">Thank you for your order!</h2>
                    <p class="checkout-success-message" data-i18n="order-success-message">{{ session('success') }}</p>
                    <p class="checkout-order-number">
                        <span data-i18n="order-number-label">Order Number:</span>
                        <strong dir="ltr">{{ session('order_number') }}</strong>
                    </p>
                    <p class="checkout-success-note" data-i18n="order-success-note">Keep this number for your records.</p>
                    <a href="{{ route('products.index') }}" class="checkout-success-link">
                        <span data-i18n="continue-shopping">Continue Shopping</span>
                        <svg viewBox="0 0 20 20" fill="none" aria-hidden="true" focusable="false">
                            <path d="M4 10h12m-5-5 5 5-5 5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                </div>
            </div>
        @endif

        @if($cart && $cart->items->isNotEmpty())

            @if($errors->any())
                <div class="checkout-error-summary" role="alert">
                    <p class="checkout-error-title" data-i18n="checkout-error-title">Please review the highlighted information.</p>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="checkout-layout">

                {{-- Customer Information --}}
                <div class="checkout-form-column">

                    <form action="{{ route('checkout.store') }}" method="POST" class="checkout-form">
                        @csrf

                        <div class="checkout-card">
                            <h2 data-i18n="customer-information">Customer Information</h2>

                            <div class="checkout-fields-grid">

                                <div class="checkout-field">
                                    <label class="checkout-label" data-i18n="full-name">Full Name *</label>
                                    <input
                                        type="text"
                                        name="customer_name"
                                        value="{{ old('customer_name', auth()->user()->name) }}"
                                        required
                                        class="checkout-input"
                                    >
                                </div>

                                <div class="checkout-field">
                                    <label class="checkout-label" data-i18n="email">Email</label>
                                    <input
                                        type="email"
                                        name="customer_email"
                                        value="{{ old('customer_email', auth()->user()->email) }}"
                                        class="checkout-input"
                                    >
                                </div>

                                <div class="checkout-field is-wide">
                                    <label class="checkout-label" data-i18n="phone-number">Phone Number *</label>
                                    <input
                                        type="tel"
                                        name="customer_phone"
                                        value="{{ old('customer_phone') }}"
                                        required
                                        class="checkout-input"
                                    >
                                </div>

                            </div>
                        </div>

                        {{-- Shipping Information --}}
                        <div class="checkout-card">
                            <h2 data-i18n="shipping-address">Shipping Address</h2>

                            <div class="checkout-fields-grid">

                                <div class="checkout-field is-wide">
                                    <label class="checkout-label" data-i18n="address">Address *</label>
                                    <input
                                        type="text"
                                        name="shipping_address"
                                        value="{{ old('shipping_address') }}"
                                        required
                                        class="checkout-input"
                                        placeholder="Street, building, apartment..."
                                        data-i18n-placeholder="address-placeholder"
                                    >
                                </div>

                                <div class="checkout-field">
                                    <label class="checkout-label" data-i18n="city">City *</label>
                                    <input
                                        type="text"
                                        name="shipping_city"
                                        value="{{ old('shipping_city') }}"
                                        required
                                        class="checkout-input"
                                    >
                                </div>

                                <div class="checkout-field">
                                    <label class="checkout-label" data-i18n="state">State / Governorate</label>
                                    <input
                                        type="text"
                                        name="shipping_state"
                                        value="{{ old('shipping_state') }}"
                                        class="checkout-input"
                                    >
                                </div>

                                <div class="checkout-field is-wide">
                                    <label class="checkout-label" data-i18n="postal-code">Postal Code</label>
                                    <input
                                        type="text"
                                        name="shipping_postal_code"
                                        value="{{ old('shipping_postal_code') }}"
                                        class="checkout-input"
                                    >
                                </div>

                            </div>
                        </div>

                        {{-- Payment --}}
                        <div class="checkout-card">
                            <h2 data-i18n="payment-method">Payment Method</h2>

                            <div class="checkout-payment-method">
                                <strong data-i18n="cash-on-delivery">Cash on Delivery</strong>
                                <p data-i18n="cash-on-delivery-description">
                                    Pay when your order arrives. No card details are collected at checkout.
                                </p>
                            </div>
                        </div>

                        {{-- Notes --}}
                        <div class="checkout-card">
                            <label class="checkout-card-title" data-i18n="order-notes">
                                Order Notes
                            </label>

                            <textarea
                                name="customer_notes"
                                rows="4"
                                class="checkout-input checkout-textarea"
                                placeholder="Additional delivery instructions..."
                                data-i18n-placeholder="notes-placeholder"
                            >{{ old('customer_notes') }}</textarea>
                        </div>

                        <button
                            type="submit"
                            class="checkout-submit"
                            data-i18n="place-order"
                        >
                            Place cash-on-delivery order
                        </button>

                    </form>
                </div>

                {{-- Order Summary --}}
                <aside class="checkout-summary-column">
                    <div class="checkout-summary">

                        <h2 data-i18n="your-order">Your Order</h2>

                        <div class="checkout-order-items">

                            @php
                                $checkoutSubtotal = 0;
                            @endphp

                            @foreach($cart->items as $item)

                                @php
                                    $unitPrice = $item->variant?->price ?? $item->product->price;
                                    $lineTotal = (float) $unitPrice * $item->quantity;
                                    $checkoutSubtotal += $lineTotal;
                                @endphp

                                <div class="checkout-order-item">
                                    <div class="checkout-order-item-details">
                                        <p class="font-semibold">
                                            <span data-storefront-content-en="{{ $item->product->name }}" data-storefront-content-ar="{{ data_get($item->product->translations, 'ar.name') ?: $item->product->name }}">{{ $item->product->name }}</span>
                                        </p>

                                        @if($item->variant)
                                            <p class="text-sm text-gray-500">
                                                @if ($item->variant->size || filled(data_get($item->variant->translations, 'ar.size')))
                                                    <span data-storefront-content-en="{{ $item->variant->size ?? '' }}" data-storefront-content-ar="{{ data_get($item->variant->translations, 'ar.size') ?: ($item->variant->size ?? '') }}">{{ $item->variant->size }}</span>
                                                @endif
                                                @if (($item->variant->size || filled(data_get($item->variant->translations, 'ar.size'))) && ($item->variant->color || filled(data_get($item->variant->translations, 'ar.color'))))
                                                    /
                                                @endif
                                                @if ($item->variant->color || filled(data_get($item->variant->translations, 'ar.color')))
                                                    <span data-storefront-content-en="{{ $item->variant->color ?? '' }}" data-storefront-content-ar="{{ data_get($item->variant->translations, 'ar.color') ?: ($item->variant->color ?? '') }}">{{ $item->variant->color }}</span>
                                                @endif
                                            </p>
                                        @endif

                                        <p class="text-sm text-gray-500">
                                            <span data-i18n="quantity">Quantity</span>: {{ $item->quantity }}
                                        </p>
                                    </div>

                                    <span class="checkout-order-item-total" dir="ltr">
                                        {{ number_format($lineTotal, 2) }} EGP
                                    </span>
                                </div>

                            @endforeach

                        </div>

                        <hr>

                        <div class="checkout-summary-row">
                            <span data-i18n="subtotal">Subtotal</span>
                            <strong>
                                <span dir="ltr">
                                {{ number_format($checkoutSubtotal, 2) }} EGP
                                </span>
                            </strong>
                        </div>

                        <div class="checkout-summary-row">
                            <span data-i18n="shipping">Shipping</span>
                            <strong data-i18n="free">Free</strong>
                        </div>

                        <hr>

                        <div class="checkout-summary-total">
                            <span data-i18n="total">Total</span>
                            <strong dir="ltr">
                                {{ number_format($checkoutSubtotal, 2) }} EGP
                            </strong>
                        </div>

                    </div>
                </aside>

            </div>

        @elseif(!session('order_number'))

            <div class="checkout-empty">
                <h2 data-i18n="checkout-cart-empty">Your cart is empty</h2>
                <a href="{{ route('products.index') }}" data-i18n="browse-products">
                    Browse Products
                </a>
            </div>

        @endif

    </div>
</section>

@endsection
