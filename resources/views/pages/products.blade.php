@extends('layouts.store')

@section('title', request()->cookie('ak2-language') === 'ar' ? 'AK2 | المتجر' : 'AK2 | Shop')
@section('description', request()->cookie('ak2-language') === 'ar' ? 'اكتشف أحدث مجموعات AK2.' : 'Discover the latest fashion collections at AK2.')

@section('content')

<section class="products-page">

    <div class="container">

        {{-- Page Heading --}}
        <div class="products-heading">
            <span class="section-label" data-i18n="products-eyebrow">AK2 COLLECTION</span>

            <h1>
                <span data-i18n="products-title-prefix">Discover Your</span>
                <strong data-i18n="products-title-emphasis">Style</strong>
            </h1>

            <p data-i18n="products-intro">
                Explore our latest fashion collection.
            </p>
        </div>


        {{-- Categories Filter --}}
        <div class="products-categories">

            <a
                href="{{ route('products.index') }}"
                class="{{ $category === null ? 'active' : '' }}"
                data-i18n="all-products"
            >
                All Products
            </a>

            @foreach ($categories as $parent)

                <div class="products-category-group">

                    <span data-storefront-content-en="{{ $parent->name }}" data-storefront-content-ar="{{ data_get($parent->translations, 'ar.name') ?: $parent->name }}">{{ $parent->name }}</span>

                    @foreach ($parent->children as $child)

                        <a
                            href="{{ route('products.index', ['category' => $child->slug]) }}"
                            class="{{ $category?->slug === $child->slug ? 'active' : '' }}"
                        >
                            <span data-storefront-content-en="{{ $child->name }}" data-storefront-content-ar="{{ data_get($child->translations, 'ar.name') ?: $child->name }}">{{ $child->name }}</span>
                        </a>

                    @endforeach

                </div>

            @endforeach

        </div>


        {{-- Products Header --}}
        <div class="products-results">

            <div>
                @if ($category)
                    <h2 data-storefront-content-en="{{ $category->name }}" data-storefront-content-ar="{{ data_get($category->translations, 'ar.name') ?: $category->name }}">{{ $category->name }}</h2>
                @else
                    <h2 data-i18n="all-products">All Products</h2>
                @endif

                <p
                    data-i18n="products-found"
                    data-i18n-count="{{ $products->total() }}"
                >
                    {{ $products->total() }} products found
                </p>
            </div>

        </div>


        {{-- Products Grid --}}
        @if ($products->isNotEmpty())

            <div class="products-grid">

                @foreach ($products as $product)

                    <article class="product-card">

                        <a
                            href="{{ route('products.show', $product->slug) }}"
                            class="product-image"
                        >

                            @php
                                $primaryImage = $product->images->first();
                                $productNameArabic = data_get($product->translations, 'ar.name') ?: $product->name;
                                $productCategoryArabic = data_get($product->category->translations, 'ar.name') ?: $product->category->name;
                                $productAltEnglish = $primaryImage?->alt_text ?? $product->name;
                                $productAltArabic = data_get($primaryImage?->translations, 'ar.alt_text') ?: $productNameArabic;
                            @endphp

                            @if ($primaryImage)

                                <img
                                    src="{{ Storage::url($primaryImage->image_path) }}"
                                    alt="{{ $productAltEnglish }}"
                                    data-storefront-alt-en="{{ $productAltEnglish }}"
                                    data-storefront-alt-ar="{{ $productAltArabic }}"
                                    loading="lazy"
                                >

                            @else

                                <div class="product-no-image">
                                    <span data-i18n="product-image-placeholder">Image coming soon</span>
                                </div>

                            @endif

                            @if ($product->is_featured)
                                <span class="product-badge" data-i18n="featured">
                                    Featured
                                </span>
                            @endif

                        </a>


                        <div class="product-information">

                            <span class="product-category">
                                <span data-storefront-content-en="{{ $product->category->name }}" data-storefront-content-ar="{{ $productCategoryArabic }}">{{ $product->category->name }}</span>
                            </span>

                            <h3>
                                <a href="{{ route('products.show', $product->slug) }}">
                                    <span data-storefront-content-en="{{ $product->name }}" data-storefront-content-ar="{{ $productNameArabic }}">{{ $product->name }}</span>
                                </a>
                            </h3>


                            <div class="product-pricing">

                                <span class="product-price" dir="ltr">
                                    EGP {{ number_format((float) $product->price, 2) }}
                                </span>

                                @if (
                                    $product->compare_at_price &&
                                    $product->compare_at_price > $product->price
                                )

                                    <span class="product-old-price" dir="ltr">
                                        EGP {{ number_format((float) $product->compare_at_price, 2) }}
                                    </span>

                                @endif

                            </div>

                            <a
                                href="{{ route('products.show', $product->slug) }}"
                                class="product-view-button"
                                data-i18n="view-product"
                            >
                                View Product
                            </a>

                        </div>

                    </article>

                @endforeach

            </div>


            {{-- Pagination --}}
            <div class="products-pagination">
                {{ $products->links() }}
            </div>

        @else

            <div class="products-empty">

                <h2 data-i18n="products-empty-title">No Products Found</h2>

                <p data-i18n="products-empty-description">
                    There are currently no products in this category.
                </p>

                <a href="{{ route('products.index') }}" data-i18n="browse-all-products">
                    Browse All Products
                </a>

            </div>

        @endif

    </div>

</section>

@endsection