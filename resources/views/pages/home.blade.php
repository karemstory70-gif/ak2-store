@extends('layouts.store')

@section('title', $page?->meta_title ?: $page?->title ?: 'AK2 | Fashion Store')
@section('description', $page?->meta_description ?: 'Discover your identity through fashion with AK2.')

@section('content')

    @php
        $heroCopy = $homeSections['hero'] ?? [];
        $heroImagePath = $page?->hero_image_path ?: $heroBanner?->image_path;
        $storefrontText = static function ($english, $arabic = null): array {
            $english = (string) ($english ?? '');

            return [
                'en' => $english,
                'ar' => filled($arabic) ? (string) $arabic : $english,
            ];
        };
        $storefrontSectionText = static function (array $section, string $field, string $englishFallback, string $arabicFallback) use ($storefrontText): array {
            $english = $section[$field] ?? $englishFallback;
            $arabic = data_get($section, "translations.ar.{$field}");

            if (! filled($arabic)) {
                $arabic = filled($section[$field] ?? null)
                    ? $section[$field]
                    : $arabicFallback;
            }

            return $storefrontText($english, $arabic);
        };
        $heroEyebrow = $heroCopy['eyebrow'] ?? $page?->eyebrow ?? $heroBanner?->title ?? 'AK2 FASHION STORE';
        $heroHeading = $heroCopy['heading'] ?? $page?->heading ?? $heroBanner?->title ?? 'Discover the collection';
        $heroBody = $heroCopy['body'] ?? $page?->subheading ?? $heroBanner?->subtitle ?? 'Discover the latest AK2 collection.';
        $heroCtaText = $heroCopy['cta_text'] ?? $page?->primary_cta_text ?? $heroBanner?->button_text ?? 'EXPLORE COLLECTION';
        $heroEyebrowArabic = data_get($heroCopy, 'translations.ar.eyebrow')
            ?: (filled($heroCopy['eyebrow'] ?? null)
                ? $heroCopy['eyebrow']
                : (filled($page?->eyebrow)
                    ? data_get($page?->translations, 'ar.eyebrow') ?: $page->eyebrow
                    : (filled($heroBanner?->title)
                        ? data_get($heroBanner?->translations, 'ar.title') ?: $heroBanner->title
                        : 'متجر AK2 للأزياء')));
        $heroHeadingArabic = data_get($heroCopy, 'translations.ar.heading')
            ?: (filled($heroCopy['heading'] ?? null)
                ? $heroCopy['heading']
                : (filled($page?->heading)
                    ? data_get($page?->translations, 'ar.heading') ?: $page->heading
                    : (filled($heroBanner?->title)
                        ? data_get($heroBanner?->translations, 'ar.title') ?: $heroBanner->title
                        : 'اكتشف المجموعة')));
        $heroBodyArabic = data_get($heroCopy, 'translations.ar.body')
            ?: (filled($heroCopy['body'] ?? null)
                ? $heroCopy['body']
                : (filled($page?->subheading)
                    ? data_get($page?->translations, 'ar.subheading') ?: $page->subheading
                    : (filled($heroBanner?->subtitle)
                        ? data_get($heroBanner?->translations, 'ar.subtitle') ?: $heroBanner->subtitle
                        : 'اكتشف أحدث مجموعات AK2.')));
        $heroCtaTextArabic = data_get($heroCopy, 'translations.ar.cta_text')
            ?: (filled($heroCopy['cta_text'] ?? null)
                ? $heroCopy['cta_text']
                : (filled($page?->primary_cta_text)
                    ? data_get($page?->translations, 'ar.primary_cta_text') ?: $page->primary_cta_text
                    : (filled($heroBanner?->button_text)
                        ? data_get($heroBanner?->translations, 'ar.button_text') ?: $heroBanner->button_text
                        : 'استكشف المجموعة')));
    @endphp

    @if (($homeSections['hero']['is_active'] ?? true))
    <!-- Hero Section -->
    <section class="hero-section">

        <div class="container hero-container">

            <div class="hero-content">

                @if ($page || $heroBanner)
                    @php
                        $eyebrowText = $storefrontText($heroEyebrow, $heroEyebrowArabic);
                    @endphp
                    <span
                        class="hero-eyebrow"
                        data-storefront-content-en="{{ $eyebrowText['en'] }}"
                        data-storefront-content-ar="{{ $eyebrowText['ar'] }}"
                    >{{ $eyebrowText['en'] }}</span>
                    @php
                        $headingText = $storefrontText($heroHeading, $heroHeadingArabic);
                    @endphp
                    <h1
                        data-storefront-content-en="{{ $headingText['en'] }}"
                        data-storefront-content-ar="{{ $headingText['ar'] }}"
                    >{{ $headingText['en'] }}</h1>
                    @php
                        $bodyText = $storefrontText($heroBody, $heroBodyArabic);
                    @endphp
                    <p
                        data-storefront-content-en="{{ $bodyText['en'] }}"
                        data-storefront-content-ar="{{ $bodyText['ar'] }}"
                    >{{ $bodyText['en'] }}</p>
                    @if (($page?->primary_cta_text ?? null) || ($heroBanner?->button_text ?? null) || ($heroCopy['cta_text'] ?? null) || ($heroCopy['cta_url'] ?? null) || ($page?->primary_cta_url ?? null) || ($heroBanner?->button_url ?? null))
                        <a href="{{ $heroCopy['cta_url'] ?? $page?->primary_cta_url ?? $heroBanner?->button_url ?? route('products.index') }}" class="hero-button">
                            @php
                                $ctaText = $storefrontText($heroCtaText, $heroCtaTextArabic);
                            @endphp
                            <span
                                data-storefront-content-en="{{ $ctaText['en'] }}"
                                data-storefront-content-ar="{{ $ctaText['ar'] }}"
                            >{{ $ctaText['en'] }}</span>
                            <span>→</span>
                        </a>
                    @else
                        <a href="{{ route('products.index') }}" class="hero-button"><span data-i18n="explore-collection">EXPLORE COLLECTION</span> <span>→</span></a>
                    @endif
                @else
                    <span class="hero-eyebrow" data-i18n="home-hero-eyebrow">AK2 FASHION STORE</span>
                    <h1><span data-i18n="home-hero-title-first">YOUR STYLE.</span><span data-i18n="home-hero-title-second">YOUR IDENTITY.</span></h1>
                    <p data-i18n="home-hero-description">Discover the latest fashion collections and express yourself with AK2.</p>
                    <a href="{{ route('products.index') }}" class="hero-button">
                        <span data-i18n="explore-collection">EXPLORE COLLECTION</span>
                        <span>→</span>
                    </a>
                @endif

                <div class="hero-indicators">
                    <span class="active"></span>
                    <span></span>
                    <span></span>
                </div>

            </div>

            <div class="hero-visual">
                @if ($heroImagePath)
                    <img
                        src="{{ Storage::disk('public')->url($heroImagePath) }}"
                    alt="{{ $heroBanner?->alt_text ?? $page?->heading ?? 'AK2 fashion collection' }}"
                    data-storefront-alt-en="{{ $heroBanner?->alt_text ?? $page?->heading ?? 'AK2 fashion collection' }}"
                    data-storefront-alt-ar="{{ data_get($heroBanner?->translations, 'ar.alt_text') ?: data_get($page?->translations, 'ar.heading') ?: ($heroBanner?->alt_text ?? $page?->heading ?? 'AK2 fashion collection') }}"
                        loading="eager"
                        style="width: 100%; height: 100%; object-fit: cover;"
                    >
                @else
                    <div class="hero-image-placeholder">
                        <span>AK2</span>
                        <p data-i18n="home-image-placeholder">YOUR NEXT LOOK STARTS HERE</p>
                    </div>
                @endif
            </div>

        </div>

    </section>
    @endif

    <!-- Shop By Category -->
    @if (($homeSections['categories']['is_active'] ?? true))
    <section class="categories-section">

        <div class="container">

            <div class="section-heading">
                @php
                    $categoriesSection = $homeSections['categories'] ?? [];
                    $categoriesEyebrow = $storefrontSectionText($categoriesSection, 'eyebrow', 'EXPLORE AK2', 'اكتشف AK2');
                    $categoriesHeading = $storefrontSectionText($categoriesSection, 'heading', 'SHOP BY CATEGORY', 'تسوق حسب التصنيف');
                    $categoriesBody = $storefrontSectionText($categoriesSection, 'body', 'Find your style. Discover your collection.', 'اعثر على أسلوبك واكتشف مجموعتك.');
                @endphp
                <span class="section-eyebrow" data-storefront-content-en="{{ $categoriesEyebrow['en'] }}" data-storefront-content-ar="{{ $categoriesEyebrow['ar'] }}">{{ $categoriesEyebrow['en'] }}</span>
                <h2 data-storefront-content-en="{{ $categoriesHeading['en'] }}" data-storefront-content-ar="{{ $categoriesHeading['ar'] }}">{{ $categoriesHeading['en'] }}</h2>
                <p data-storefront-content-en="{{ $categoriesBody['en'] }}" data-storefront-content-ar="{{ $categoriesBody['ar'] }}">{{ $categoriesBody['en'] }}</p>
            </div>

            <div class="categories-grid">
                @foreach ($categories as $category)
                    @php
                        $categoryClass = match ($loop->index % 4) {
                            0 => 'category-men',
                            1 => 'category-women',
                            2 => 'category-kids',
                            default => 'category-shoes',
                        };
                    @endphp
                    @php
                        $categoryName = $storefrontText($category->name, data_get($category->translations, 'ar.name'));
                        $categoryDescription = $storefrontText(
                            $category->description ?: 'Discover the collection',
                            data_get($category->translations, 'ar.description')
                                ?: ($category->description ? $category->description : 'اكتشف المجموعة'),
                        );
                    @endphp
                    <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="category-card {{ $categoryClass }}">
                        @if ($category->image_path)
                            <img src="{{ Storage::disk('public')->url($category->image_path) }}" alt="{{ $categoryName['en'] }}" data-storefront-alt-en="{{ $categoryName['en'] }}" data-storefront-alt-ar="{{ $categoryName['ar'] }}" loading="lazy" style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover;">
                        @endif
                        <div class="category-overlay"></div>
                        <div class="category-content">
                            <span>{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }} / <span data-i18n="category-collection">COLLECTION</span></span>
                            <h3 data-storefront-content-en="{{ $categoryName['en'] }}" data-storefront-content-ar="{{ $categoryName['ar'] }}">{{ $categoryName['en'] }}</h3>
                            <p data-storefront-content-en="{{ $categoryDescription['en'] }}" data-storefront-content-ar="{{ $categoryDescription['ar'] }}">{{ $categoryDescription['en'] }}</p>
                            <strong><span data-i18n="explore-collection">EXPLORE</span> →</strong>
                        </div>
                    </a>
                @endforeach
            </div>

        </div>

    </section>
    @endif

    <!-- Featured Products -->
    @if (($homeSections['featured_products']['is_active'] ?? true))
    <section class="featured-section">

        <div class="container">

            <div class="featured-header">
                <div class="section-heading featured-title">
                    @php
                        $featuredSection = $homeSections['featured_products'] ?? [];
                        $featuredEyebrow = $storefrontSectionText($featuredSection, 'eyebrow', 'HANDPICKED FOR YOU', 'اختياراتنا لك');
                        $featuredHeading = $storefrontSectionText($featuredSection, 'heading', 'FEATURED PRODUCTS', 'منتجات مختارة');
                        $featuredBody = $storefrontSectionText($featuredSection, 'body', 'Discover pieces that define your style.', 'اكتشف قطعاً تعبّر عن أسلوبك.');
                    @endphp
                    <span class="section-eyebrow" data-storefront-content-en="{{ $featuredEyebrow['en'] }}" data-storefront-content-ar="{{ $featuredEyebrow['ar'] }}">{{ $featuredEyebrow['en'] }}</span>
                    <h2 data-storefront-content-en="{{ $featuredHeading['en'] }}" data-storefront-content-ar="{{ $featuredHeading['ar'] }}">{{ $featuredHeading['en'] }}</h2>
                    <p data-storefront-content-en="{{ $featuredBody['en'] }}" data-storefront-content-ar="{{ $featuredBody['ar'] }}">{{ $featuredBody['en'] }}</p>
                </div>

                <a href="{{ route('products.index') }}" class="view-all-link">
                    <span data-i18n="shop-all-products">VIEW ALL PRODUCTS</span> →
                </a>
            </div>

            <div class="products-grid">
                @forelse ($featuredProducts as $product)
                    @php
                        $primaryImage = $product->images->first();
                    @endphp
                    <article class="product-card">
                        @php
                            $productName = $storefrontText($product->name, data_get($product->translations, 'ar.name'));
                            $productCategory = $storefrontText($product->category?->name ?? 'COLLECTION', data_get($product->category?->translations, 'ar.name') ?: ($product->category?->name ?? 'مجموعة'));
                            $productImageAlt = $storefrontText(
                                $primaryImage?->alt_text ?? $product->name,
                                data_get($primaryImage?->translations, 'ar.alt_text') ?: data_get($product->translations, 'ar.name'),
                            );
                        @endphp
                        <a href="{{ route('products.show', $product->slug) }}" class="product-image">
                            <span class="product-badge" data-i18n="featured">FEATURED</span>
                            @if ($primaryImage)
                                <img src="{{ Storage::disk('public')->url($primaryImage->image_path) }}" alt="{{ $productImageAlt['en'] }}" data-storefront-alt-en="{{ $productImageAlt['en'] }}" data-storefront-alt-ar="{{ $productImageAlt['ar'] }}" loading="lazy" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <span class="product-image-placeholder">AK2</span>
                            @endif
                            <span class="quick-view" data-i18n="view-product">VIEW PRODUCT</span>
                        </a>
                        <div class="product-info">
                            <span class="product-category" data-storefront-content-en="{{ $productCategory['en'] }}" data-storefront-content-ar="{{ $productCategory['ar'] }}">{{ $productCategory['en'] }}</span>
                            <h3 data-storefront-content-en="{{ $productName['en'] }}" data-storefront-content-ar="{{ $productName['ar'] }}">{{ $productName['en'] }}</h3>
                            <div class="product-price">EGP {{ number_format((float) $product->price, 2) }}</div>
                            <a href="{{ route('products.show', $product->slug) }}" class="product-action" data-i18n="view-product">EXPLORE PRODUCT</a>
                        </div>
                    </article>
                @empty
                    <p data-i18n="home-no-featured-products">No featured products are published yet.</p>
                @endforelse
            </div>

        </div>

    </section>
    @endif

    <!-- New Arrivals -->
    @if (($homeSections['new_arrivals']['is_active'] ?? true))
    <section class="new-arrivals-section">

        <div class="container">

            <div class="new-arrivals-header">

                <div class="section-heading">
                    @php
                        $newArrivalsSection = $homeSections['new_arrivals'] ?? [];
                        $newArrivalsEyebrow = $storefrontSectionText($newArrivalsSection, 'eyebrow', 'JUST DROPPED', 'وصل حديثاً');
                        $newArrivalsHeading = $storefrontSectionText($newArrivalsSection, 'heading', 'NEW ARRIVALS', 'وصل حديثاً');
                        $newArrivalsBody = $storefrontSectionText($newArrivalsSection, 'body', 'Fresh styles. New possibilities.', 'أناقة متجددة. خيارات جديدة.');
                    @endphp
                    <span class="section-eyebrow" data-storefront-content-en="{{ $newArrivalsEyebrow['en'] }}" data-storefront-content-ar="{{ $newArrivalsEyebrow['ar'] }}">{{ $newArrivalsEyebrow['en'] }}</span>
                    <h2 data-storefront-content-en="{{ $newArrivalsHeading['en'] }}" data-storefront-content-ar="{{ $newArrivalsHeading['ar'] }}">{{ $newArrivalsHeading['en'] }}</h2>
                    <p data-storefront-content-en="{{ $newArrivalsBody['en'] }}" data-storefront-content-ar="{{ $newArrivalsBody['ar'] }}">{{ $newArrivalsBody['en'] }}</p>
                </div>

                <a href="{{ route('products.index') }}" class="view-all-link">
                    <span data-i18n="shop-all-products">DISCOVER ALL</span> →
                </a>

            </div>

            <div class="products-grid">
                @forelse ($newArrivals as $product)
                    @php
                        $primaryImage = $product->images->first();
                    @endphp
                    <article class="product-card new-product-card">
                        @php
                            $productName = $storefrontText($product->name, data_get($product->translations, 'ar.name'));
                            $productCategory = $storefrontText($product->category?->name ?? 'COLLECTION', data_get($product->category?->translations, 'ar.name') ?: ($product->category?->name ?? 'مجموعة'));
                            $productImageAlt = $storefrontText(
                                $primaryImage?->alt_text ?? $product->name,
                                data_get($primaryImage?->translations, 'ar.alt_text') ?: data_get($product->translations, 'ar.name'),
                            );
                        @endphp
                        <a href="{{ route('products.show', $product->slug) }}" class="product-image">
                            <span class="product-badge new-badge" data-i18n="new">NEW</span>
                            @if ($primaryImage)
                                <img src="{{ Storage::disk('public')->url($primaryImage->image_path) }}" alt="{{ $productImageAlt['en'] }}" data-storefront-alt-en="{{ $productImageAlt['en'] }}" data-storefront-alt-ar="{{ $productImageAlt['ar'] }}" loading="lazy" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <span class="product-image-placeholder">AK2</span>
                            @endif
                            <span class="quick-view" data-i18n="view-product">VIEW PRODUCT</span>
                        </a>
                        <div class="product-info">
                            <span class="product-category" data-storefront-content-en="{{ $productCategory['en'] }}" data-storefront-content-ar="{{ $productCategory['ar'] }}">{{ $productCategory['en'] }}</span>
                            <h3 data-storefront-content-en="{{ $productName['en'] }}" data-storefront-content-ar="{{ $productName['ar'] }}">{{ $productName['en'] }}</h3>
                            <div class="product-price">EGP {{ number_format((float) $product->price, 2) }}</div>
                            <a href="{{ route('products.show', $product->slug) }}" class="product-action" data-i18n="view-product">EXPLORE PRODUCT</a>
                        </div>
                    </article>
                @empty
                    <p data-i18n="home-no-new-products">No published products are available yet.</p>
                @endforelse
            </div>

        </div>

    </section>
    @endif

    <!-- Promotional Banner -->
    @if (($homeSections['promotion']['is_active'] ?? true))
    <section class="promotion-section">

        <div class="container">

            @php
                $promotionSection = $homeSections['promotion'] ?? [];
                $promotionEyebrow = $promotionSection['eyebrow'] ?? 'AK2 EXCLUSIVE OFFER';
                $promotionHeading = $promotionSection['heading'] ?? $promotionBanner?->title ?? 'ELEVATE YOUR EVERYDAY STYLE.';
                $promotionBody = $promotionSection['body'] ?? $promotionBanner?->subtitle ?? 'Discover your next favorite look. Explore our latest fashion collections and find the style that represents you.';
                $promotionCtaText = $promotionSection['cta_text'] ?? $promotionBanner?->button_text ?? 'SHOP THE COLLECTION';
                $promotionEyebrowArabic = data_get($promotionSection, 'translations.ar.eyebrow')
                    ?: (filled($promotionSection['eyebrow'] ?? null)
                        ? $promotionSection['eyebrow']
                        : 'عرض حصري من AK2');
                $promotionHeadingArabic = data_get($promotionSection, 'translations.ar.heading')
                    ?: (filled($promotionSection['heading'] ?? null)
                        ? $promotionSection['heading']
                        : (filled($promotionBanner?->title)
                            ? data_get($promotionBanner?->translations, 'ar.title') ?: $promotionBanner->title
                            : 'ارتقِ بأناقتك اليومية.'));
                $promotionBodyArabic = data_get($promotionSection, 'translations.ar.body')
                    ?: (filled($promotionSection['body'] ?? null)
                        ? $promotionSection['body']
                        : (filled($promotionBanner?->subtitle)
                            ? data_get($promotionBanner?->translations, 'ar.subtitle') ?: $promotionBanner->subtitle
                            : 'اكتشف إطلالتك المفضلة القادمة. تصفح أحدث مجموعات الأزياء واختر ما يعبر عن أسلوبك.'));
                $promotionCtaArabic = data_get($promotionSection, 'translations.ar.cta_text')
                    ?: (filled($promotionSection['cta_text'] ?? null)
                        ? $promotionSection['cta_text']
                        : (filled($promotionBanner?->button_text)
                            ? data_get($promotionBanner?->translations, 'ar.button_text') ?: $promotionBanner->button_text
                            : 'تسوق المجموعة'));
                $promotionEyebrowText = $storefrontText($promotionEyebrow, $promotionEyebrowArabic);
                $promotionHeadingText = $storefrontText($promotionHeading, $promotionHeadingArabic);
                $promotionBodyText = $storefrontText($promotionBody, $promotionBodyArabic);
                $promotionCtaText = $storefrontText($promotionCtaText, $promotionCtaArabic);
                $promotionImagePath = $promotionSection['image_path'] ?? $promotionBanner?->image_path;
                $promotionImageAlt = $promotionBanner?->alt_text ?? $promotionBanner?->title ?? 'AK2 promotion';
                $promotionImageAltArabic = data_get($promotionBanner?->translations, 'ar.alt_text')
                    ?: data_get($promotionBanner?->translations, 'ar.title')
                    ?: $promotionImageAlt;
            @endphp
            <div class="promotion-banner{{ $promotionImagePath ? ' has-background-image' : '' }}">
                @if ($promotionImagePath)
                    <img class="promotion-background-image" src="{{ Storage::disk('public')->url($promotionImagePath) }}" alt="{{ $promotionImageAlt }}" data-storefront-alt-en="{{ $promotionImageAlt }}" data-storefront-alt-ar="{{ $promotionImageAltArabic }}" loading="lazy">
                @endif
                <div class="promotion-content">
                    <span class="promotion-eyebrow" data-storefront-content-en="{{ $promotionEyebrowText['en'] }}" data-storefront-content-ar="{{ $promotionEyebrowText['ar'] }}">{{ $promotionEyebrowText['en'] }}</span>
                    <h2 data-storefront-content-en="{{ $promotionHeadingText['en'] }}" data-storefront-content-ar="{{ $promotionHeadingText['ar'] }}">{{ $promotionHeadingText['en'] }}</h2>
                    <p data-storefront-content-en="{{ $promotionBodyText['en'] }}" data-storefront-content-ar="{{ $promotionBodyText['ar'] }}">{{ $promotionBodyText['en'] }}</p>
                    @if (($homeSections['promotion']['cta_url'] ?? null) || $promotionBanner?->button_url || ! $promotionBanner)
                        <a href="{{ $homeSections['promotion']['cta_url'] ?? $promotionBanner?->button_url ?? route('products.index') }}" class="promotion-button">
                            <span data-storefront-content-en="{{ $promotionCtaText['en'] }}" data-storefront-content-ar="{{ $promotionCtaText['ar'] }}">{{ $promotionCtaText['en'] }}</span>
                            <span>→</span>
                        </a>
                    @endif
                </div>

                <div class="promotion-decoration">
                    <span>AK2</span>
                    <span data-i18n="style">STYLE</span>
                    <span data-i18n="identity">IDENTITY</span>
                </div>

                <div class="promotion-number">
                    02
                </div>

            </div>

        </div>

    </section>
    @endif

    @if (isset($homeSections['content']) && ($homeSections['content']['is_active'] ?? true))
        <section class="ak2-home-content">
            <div class="container">
                @if (! empty($homeSections['content']['eyebrow']) || filled(data_get($homeSections['content'], 'translations.ar.eyebrow')))
                    <p class="ak2-home-content__eyebrow" data-storefront-content-en="{{ $homeSections['content']['eyebrow'] ?? '' }}" data-storefront-content-ar="{{ data_get($homeSections['content'], 'translations.ar.eyebrow') ?: ($homeSections['content']['eyebrow'] ?? '') }}">{{ $homeSections['content']['eyebrow'] ?? '' }}</p>
                @endif
                @if (! empty($homeSections['content']['heading']) || filled(data_get($homeSections['content'], 'translations.ar.heading')))
                    <h2 data-storefront-content-en="{{ $homeSections['content']['heading'] ?? '' }}" data-storefront-content-ar="{{ data_get($homeSections['content'], 'translations.ar.heading') ?: ($homeSections['content']['heading'] ?? '') }}">{{ $homeSections['content']['heading'] ?? '' }}</h2>
                @endif
                @if (! empty($homeSections['content']['body']) || filled(data_get($homeSections['content'], 'translations.ar.body')))
                    <div class="ak2-home-content__body" data-storefront-content-en="{{ $homeSections['content']['body'] ?? '' }}" data-storefront-content-ar="{{ data_get($homeSections['content'], 'translations.ar.body') ?: ($homeSections['content']['body'] ?? '') }}">{{ $homeSections['content']['body'] ?? '' }}</div>
                @endif
                @if (! empty($homeSections['content']['image_path']))
                    <img src="{{ Storage::disk('public')->url($homeSections['content']['image_path']) }}" alt="{{ $homeSections['content']['heading'] ?? 'AK2' }}" data-storefront-alt-en="{{ $homeSections['content']['heading'] ?? 'AK2' }}" data-storefront-alt-ar="{{ data_get($homeSections['content'], 'translations.ar.heading') ?: ($homeSections['content']['heading'] ?? 'AK2') }}" loading="lazy">
                @endif
                @if ((! empty($homeSections['content']['cta_text']) || filled(data_get($homeSections['content'], 'translations.ar.cta_text'))) && ! empty($homeSections['content']['cta_url']))
                    <a class="ak2-content-page__button" href="{{ $homeSections['content']['cta_url'] }}" data-storefront-content-en="{{ $homeSections['content']['cta_text'] ?? '' }}" data-storefront-content-ar="{{ data_get($homeSections['content'], 'translations.ar.cta_text') ?: ($homeSections['content']['cta_text'] ?? '') }}">{{ $homeSections['content']['cta_text'] ?? '' }}</a>
                @endif
            </div>
        </section>
    @endif
    

@endsection

