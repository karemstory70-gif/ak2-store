
@extends('layouts.store')

@section('title', request()->cookie('ak2-language') === 'ar' ? 'AK2 | التصنيفات' : 'AK2 | Categories')
@section('description', request()->cookie('ak2-language') === 'ar' ? 'اكتشف مجموعات AK2 واختر ما يناسبك.' : 'Explore AK2 collections and find the style that fits you.')

@section('content')
    <section class="categories-section categories-page">
        <div class="container">
            <header class="categories-page__heading">
                <span class="section-eyebrow" data-i18n="categories-eyebrow">EXPLORE AK2</span>
                <h1><span data-i18n="categories-title">Find your collection</span><span>.</span></h1>
                <p data-i18n="categories-intro">Explore the collections, then choose a category to see available styles.</p>
            </header>

            <div class="categories-page__grid">
            @forelse ($categories as $category)
                @php
                    $categoryNameArabic = data_get($category->translations, 'ar.name') ?: $category->name;
                    $categoryDescriptionArabic = data_get($category->translations, 'ar.description') ?: ($category->description ?: 'Discover the collection and find your next look.');
                    $categoryClass = match ($loop->index % 4) {
                        0 => 'category-men',
                        1 => 'category-women',
                        2 => 'category-kids',
                        default => 'category-shoes',
                    };
                @endphp

                <section class="category-collection" aria-labelledby="category-{{ $category->id }}">
                    <a
                        href="{{ route('products.index', ['category' => $category->slug]) }}"
                        class="category-card {{ $categoryClass }}"
                    >
                        @if ($category->image_path)
                            <img
                                src="{{ Storage::disk('public')->url($category->image_path) }}"
                                alt="{{ $category->name }}"
                                data-storefront-alt-en="{{ $category->name }}"
                                data-storefront-alt-ar="{{ $categoryNameArabic }}"
                                loading="lazy"
                            >
                        @endif
                        <span class="category-overlay" aria-hidden="true"></span>
                        <div class="category-content">
                            <span>{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }} / <span data-i18n="category-collection">COLLECTION</span></span>
                            <h2
                                id="category-{{ $category->id }}"
                                data-storefront-content-en="{{ $category->name }}"
                                data-storefront-content-ar="{{ $categoryNameArabic }}"
                            >{{ $category->name }}</h2>
                            @if ($category->description || filled(data_get($category->translations, 'ar.description')))
                                <p data-storefront-content-en="{{ $category->description ?? '' }}" data-storefront-content-ar="{{ $categoryDescriptionArabic }}">{{ $category->description }}</p>
                            @else
                                <p data-i18n="category-card-description">Discover the collection and find your next look.</p>
                            @endif
                            <strong><span data-i18n="explore-collection">EXPLORE COLLECTION</span> <span aria-hidden="true">→</span></strong>
                        </div>
                    </a>

                    @if ($category->children->isNotEmpty())
                        <nav
                            class="category-child-links"
                            aria-label="{{ $category->name }} subcategories"
                            data-i18n-aria="subcategories"
                            data-i18n-category="{{ $category->name }}"
                            data-storefront-category-en="{{ $category->name }}"
                            data-storefront-category-ar="{{ $categoryNameArabic }}"
                        >
                            @foreach ($category->children as $child)
                                <a href="{{ route('products.index', ['category' => $child->slug]) }}">
                                    <span data-storefront-content-en="{{ $child->name }}" data-storefront-content-ar="{{ data_get($child->translations, 'ar.name') ?: $child->name }}">{{ $child->name }}</span>
                                    <span aria-hidden="true">↗</span>
                                </a>
                            @endforeach
                        </nav>
                    @endif
                </section>
            @empty
                <div class="categories-page__empty">
                    <span class="section-eyebrow" data-i18n="categories-empty-eyebrow">COMING SOON</span>
                    <h2 data-i18n="categories-empty-title">Collections are being prepared.</h2>
                    <p data-i18n="categories-empty-description">Browse all available products while we add more collections.</p>
                    <a class="hero-button" href="{{ route('products.index') }}">
                        <span data-i18n="shop-all-products">SHOP ALL PRODUCTS</span>
                        <span aria-hidden="true">→</span>
                    </a>
                </div>
            @endforelse
            </div>
        </div>
    </section>
@endsection
