
@php
    $storeLanguage = request()->cookie('ak2-language') === 'ar' ? 'ar' : 'en';
    $storeMetaTitle = trim($__env->yieldContent('title', 'AK2 | Fashion Store'));
    $storeMetaDescription = trim($__env->yieldContent('description', 'AK2 — Discover your style.'));

    $storeMetaTitleArabic = $storeMetaTitle;
    $storeMetaDescriptionArabic = $storeMetaDescription;

    if (isset($product)) {
        $storeMetaTitleArabic = 'AK2 | '.(data_get($product->translations, 'ar.name') ?: $product->name);
        $storeMetaDescriptionArabic = data_get($product->translations, 'ar.description')
            ?: $product->description
            ?: $storeMetaDescription;
    } elseif (isset($page)) {
        $storeMetaTitleArabic = data_get($page->translations, 'ar.meta_title')
            ?: data_get($page->translations, 'ar.title')
            ?: $page->meta_title
            ?: $page->title;
        $storeMetaDescriptionArabic = data_get($page->translations, 'ar.meta_description')
            ?: $page->meta_description
            ?: $storeMetaDescription;
    } elseif (isset($defaults)) {
        $storeMetaTitleArabic = data_get($defaults, 'translations.ar.title')
            ?: data_get($defaults, 'title')
            ?: $storeMetaTitle;
        $storeMetaDescriptionArabic = data_get($defaults, 'translations.ar.description')
            ?: data_get($defaults, 'description')
            ?: $storeMetaDescription;
    }
@endphp
<!DOCTYPE html>
<html lang="{{ $storeLanguage }}" dir="{{ $storeLanguage === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#080D18">

    <script src="{{ asset('assets/js/theme.js') }}?v=2"></script>

    <meta
        name="description"
        content="{{ $storeLanguage === 'ar' ? $storeMetaDescriptionArabic : $storeMetaDescription }}"
        @if (isset($product) || isset($page) || isset($defaults))
            data-storefront-meta-description-en="{{ $storeMetaDescription }}"
            data-storefront-meta-description-ar="{{ $storeMetaDescriptionArabic }}"
        @endif
    >

    <title
        @if (isset($product) || isset($page) || isset($defaults))
            data-storefront-meta-title-en="{{ $storeMetaTitle }}"
            data-storefront-meta-title-ar="{{ $storeMetaTitleArabic }}"
        @endif
    >{{ $storeLanguage === 'ar' ? $storeMetaTitleArabic : $storeMetaTitle }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/categories-page.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/products.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/product-details.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/cart.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/content-pages.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/themes.css') }}?v=2">
    <link rel="stylesheet" href="{{ asset('assets/css/rtl.css') }}">

    @stack('styles')
</head>

<body>

    <div class="ak2-global-backdrop" aria-hidden="true"></div>
    <div class="ak2-store-shell">

    @include('partials.announcement')

    @include('partials.header')

    <main>
        @yield('content')
    </main>

    @include('partials.footer')

    <script src="{{ asset('assets/js/language.js') }}?v=3" defer></script>
    <script src="{{ asset('assets/js/app.js') }}" defer></script>
    <script src="{{ asset('assets/js/navigation.js') }}" defer></script>
    <script src="{{ asset('assets/js/cart.js') }}" defer></script>

    @stack('scripts')

    </div>

</body>
</html>
