
@extends('layouts.store')

@section('title', 'AK2 | ' . $product->name)
@section('description', $product->description ?? $product->name)

@section('content')

<section class="product-details-page">
    <div class="container">

        @php
            $productNameArabic = data_get($product->translations, 'ar.name') ?: $product->name;
            $productDescriptionArabic = data_get($product->translations, 'ar.description') ?: $product->description;
            $categoryNameArabic = data_get($product->category->translations, 'ar.name') ?: $product->category->name;
        @endphp

        <nav class="product-breadcrumb" aria-label="Breadcrumb" data-i18n-aria="breadcrumb">
            <a href="{{ route('home') }}" data-i18n="nav-home">Home</a>
            <span>/</span>
            <a href="{{ route('products.index') }}" data-i18n="nav-shop">Shop</a>
            <span>/</span>
            <a href="{{ route('products.index', ['category' => $product->category->slug]) }}">
                <span data-storefront-content-en="{{ $product->category->name }}" data-storefront-content-ar="{{ $categoryNameArabic }}">{{ $product->category->name }}</span>
            </a>
            <span>/</span>
            <span data-storefront-content-en="{{ $product->name }}" data-storefront-content-ar="{{ $productNameArabic }}">{{ $product->name }}</span>
        </nav>

        <div class="product-details-layout">

            {{-- Product Images --}}
            <div class="product-gallery">

                @php
                    $primaryImage = $product->images->first();
                @endphp

                <div class="product-main-image">
                    @if ($primaryImage)
                        <img
                            src="{{ Storage::url($primaryImage->image_path) }}"
                            alt="{{ $primaryImage->alt_text ?? $product->name }}"
                            data-storefront-alt-en="{{ $primaryImage->alt_text ?? $product->name }}"
                            data-storefront-alt-ar="{{ data_get($primaryImage->translations, 'ar.alt_text') ?: $productNameArabic }}"
                        >
                    @else
                        <div class="product-no-image">
                            <span data-i18n="product-image-placeholder">Image coming soon</span>
                        </div>
                    @endif
                </div>

                @if ($product->images->count() > 1)
                    <div class="product-thumbnails">
                        @foreach ($product->images as $image)
                            <div class="product-thumbnail">
                                <img
                                    src="{{ Storage::url($image->image_path) }}"
                                    alt="{{ $image->alt_text ?? $product->name }}"
                                    data-storefront-alt-en="{{ $image->alt_text ?? $product->name }}"
                                    data-storefront-alt-ar="{{ data_get($image->translations, 'ar.alt_text') ?: $productNameArabic }}"
                                >
                            </div>
                        @endforeach
                    </div>
                @endif

            </div>

            {{-- Product Information --}}
            <div class="product-details-information">

                <span class="product-details-category">
                    <span data-storefront-content-en="{{ $product->category->name }}" data-storefront-content-ar="{{ $categoryNameArabic }}">{{ $product->category->name }}</span>
                </span>

                <h1 data-storefront-content-en="{{ $product->name }}" data-storefront-content-ar="{{ $productNameArabic }}">{{ $product->name }}</h1>

                <div class="product-details-pricing">
                    <span class="product-details-price">
                        <span dir="ltr">
                        EGP {{ number_format((float) $product->price, 2) }}
                        </span>
                    </span>

                    @if (
                        $product->compare_at_price &&
                        $product->compare_at_price > $product->price
                    )
                        <span class="product-details-old-price">
                            <span dir="ltr">
                            EGP {{ number_format((float) $product->compare_at_price, 2) }}
                            </span>
                        </span>
                    @endif
                </div>

                @if ($product->description || filled(data_get($product->translations, 'ar.description')))
                    <div class="product-details-description">
                        <h2 data-i18n="product-description">Product Description</h2>
                        <p data-storefront-content-en="{{ $product->description ?? '' }}" data-storefront-content-ar="{{ $productDescriptionArabic }}">{{ $product->description }}</p>
                    </div>
                @endif

                {{-- Product Purchase Form --}}

@php
    $hasVariants = $product->variants->isNotEmpty();

    $hasAvailableVariant = $product->variants->contains(
        fn ($variant) => $variant->stock_quantity > 0
    );

    $isAvailable = $hasVariants
        ? $hasAvailableVariant
        : $product->stock_quantity > 0;
@endphp

@if (session('success'))
    <div class="cart-success-message" role="status">
        {{ session('success') }}
    </div>
@endif

<form
    action="{{ route('cart.add') }}"
    method="POST"
    class="product-purchase-form"
>
    @csrf

    <input
        type="hidden"
        name="product_id"
        value="{{ $product->id }}"
    >

    {{-- Variant Selection --}}
    @if ($hasVariants)

        <div class="product-variants-section">

            <h2 data-i18n="select-option">Select Your Option</h2>

            <label for="variant_id" data-i18n="size-color">
                Size / Color
            </label>

            <select
                name="variant_id"
                id="variant_id"
                required
                dir="auto"
            >
                <option value="" data-i18n="choose-option">Choose an option</option>

                @foreach ($product->variants as $variant)

                    <option
                        value="{{ $variant->id }}"
                        @selected(old('variant_id') == $variant->id)
                        @disabled($variant->stock_quantity < 1)
                        data-i18n="variant-option"
                        data-i18n-size="{{ $variant->size ?? 'Standard' }}"
                        data-i18n-color="{{ $variant->color ? ' - ' . $variant->color : '' }}"
                        data-i18n-price="{{ $variant->price !== null ? ' - EGP ' . number_format((float) $variant->price, 2) : '' }}"
                        data-i18n-availability="{{ $variant->stock_quantity > 0 ? 'in-stock' : 'out-of-stock' }}"
                        data-i18n-stock-suffix="{{ $variant->stock_quantity > 0 ? ' (' . $variant->stock_quantity . ')' : '' }}"
                        data-storefront-size-en="{{ $variant->size ?? 'Standard' }}"
                        @if (filled(data_get($variant->translations, 'ar.size')))
                            data-storefront-size-ar="{{ data_get($variant->translations, 'ar.size') }}"
                        @endif
                        data-storefront-color-en="{{ $variant->color ? ' - ' . $variant->color : '' }}"
                        data-storefront-color-ar="{{ $variant->color ? ' - ' . (data_get($variant->translations, 'ar.color') ?: $variant->color) : '' }}"
                    >
                        {{ $variant->size ?? 'Standard' }}

                        @if ($variant->color)
                            - {{ $variant->color }}
                        @endif

                        @if ($variant->price !== null)
                            - EGP {{ number_format((float) $variant->price, 2) }}
                        @endif

                        @if ($variant->stock_quantity > 0)
                            - In Stock ({{ $variant->stock_quantity }})
                        @else
                            - Out of Stock
                        @endif

                    </option>

                @endforeach

            </select>

            @error('variant_id')
                <p class="form-error">{{ $message }}</p>
            @enderror

        </div>

    @else

        {{-- Simple Product Availability --}}

        <div class="product-variants-section">

            <h2 data-i18n="availability">Availability</h2>

            @if ($product->stock_quantity > 0)
                <p><span data-i18n="in-stock">In Stock</span> ({{ $product->stock_quantity }})</p>
            @else
                <p data-i18n="currently-out-of-stock">Currently Out of Stock</p>
            @endif

        </div>

    @endif

    {{-- Quantity --}}

    <div class="product-quantity-section">

        <label for="quantity" data-i18n="quantity">
            Quantity
        </label>

        <input
            type="number"
            name="quantity"
            id="quantity"
            value="{{ old('quantity', 1) }}"
            min="1"
            max="{{ $hasVariants ? null : $product->stock_quantity }}"
            required
            dir="ltr"
        >

        @error('quantity')
            <p class="form-error">{{ $message }}</p>
        @enderror

    </div>

    {{-- Add To Cart Button --}}

    <button
        type="submit"
        class="product-add-to-cart"
        @disabled(!$isAvailable)
    >
        @if ($isAvailable)
            <span data-i18n="add-to-cart">Add to Cart</span>
        @else
            <span data-i18n="out-of-stock">Out of Stock</span>
        @endif
    </button>

    @error('product_id')
        <p class="form-error">{{ $message }}</p>
    @enderror

</form>

<div class="product-details-note">
    <span data-i18n="product-details-note">Product information and availability are subject to change.</span>
</div>

            </div>

        </div>

    </div>
</section>

@endsection
