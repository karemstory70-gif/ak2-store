@php
    $cart = null;
    $cartCount = 0;

    if (auth()->check()) {
        $cart = auth()->user()->cart()
            ->with([
                'items.product.images' => function ($query) {
                    $query->orderByDesc('is_primary')
                        ->orderBy('sort_order');
                },
                'items.variant',
            ])
            ->first();

        if ($cart) {
            $cartCount = $cart->items->sum('quantity');
        }
    }

    $miniCartItems = $cart?->items ?? collect();
    $miniCartSubtotal = 0;
    $globalSections = $globalSections ?? [];
    $configuredNavigationLinks = collect($globalSections['navigation']['links'] ?? [])
        ->where('group', 'navigation')
        ->values();

    $navigationLinks = $configuredNavigationLinks->isNotEmpty()
        ? $configuredNavigationLinks
        : collect([
            ['url' => route('home'), 'label' => 'Home', 'translation_key' => 'nav-home'],
            ['url' => route('products.index'), 'label' => 'Shop', 'translation_key' => 'nav-shop'],
            ['url' => route('categories.index'), 'label' => 'Categories', 'translation_key' => 'nav-categories'],
            ['url' => route('about'), 'label' => 'About Us', 'translation_key' => 'nav-about'],
            ['url' => route('contact'), 'label' => 'Contact', 'translation_key' => 'nav-contact'],
        ]);

    $existingNavigationPaths = $navigationLinks
        ->map(fn (array $link): ?string => parse_url($link['url'] ?? '', PHP_URL_PATH))
        ->filter()
        ->all();

    $publishedPageLinks = collect($publishedNavigationPages ?? [])
        ->map(fn ($page): array => [
            'url' => route('pages.show', ['slug' => $page->slug]),
            'label' => $page->title,
            'translations' => $page->translations,
        ])
        ->reject(fn (array $link): bool => in_array(parse_url($link['url'], PHP_URL_PATH), $existingNavigationPaths, true));

    $navigationLinks = $navigationLinks->concat($publishedPageLinks)->values();

    foreach ($miniCartItems as $item) {
        $unitPrice = $item->variant?->price ?? $item->product->price;

        $miniCartSubtotal += $unitPrice * $item->quantity;
    }
@endphp

<header class="site-header">
    <div class="container header-container">

        {{-- Brand Logo --}}
        <a href="{{ route('home') }}" class="brand-logo">
            @if (! empty($globalSections['navigation']['image_path']))
                <img
                    src="{{ Storage::disk('public')->url($globalSections['navigation']['image_path']) }}"
                    alt="{{ $globalSections['navigation']['heading'] ?? 'AK2 Technologies - Engineering the Digital Future' }}"
                    data-storefront-alt-en="{{ $globalSections['navigation']['heading'] ?? 'AK2 Technologies - Engineering the Digital Future' }}"
                    data-storefront-alt-ar="{{ data_get($globalSections['navigation'], 'translations.ar.heading') ?: ($globalSections['navigation']['heading'] ?? 'AK2 Technologies - Engineering the Digital Future') }}"
                >
            @else
                <img
                    src="{{ asset('assets/images/ak2-logo.png') }}"
                    alt="AK2 Technologies - Engineering the Digital Future"
                    data-storefront-alt-en="AK2 Technologies - Engineering the Digital Future"
                    data-storefront-alt-ar="AK2 Technologies - هندسة المستقبل الرقمي"
                >
            @endif
        </a>

        {{-- Main Navigation --}}
        <nav class="main-navigation" id="mainNavigation">
            @foreach ($navigationLinks as $link)
                <a
                    href="{{ $link['url'] }}"
                    class="nav-link"
                    @if (! empty($link['translation_key'])) data-i18n="{{ $link['translation_key'] }}" @endif
                    @if (empty($link['translation_key']))
                        data-storefront-content-en="{{ $link['label'] }}"
                        data-storefront-content-ar="{{ data_get($link, 'translations.ar.label') ?: $link['label'] }}"
                    @endif
                >{{ $link['label'] }}</a>
            @endforeach
        </nav>

        {{-- Header Actions --}}
        <div class="header-actions">

            {{-- Shopping Cart --}}
            <details class="cart-dropdown">

                <summary class="header-icon cart-link" aria-label="Shopping cart" data-i18n-aria="shopping-cart">
                    <span class="cart-icon-wrapper">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M3 3h2l2.5 12h10L21 7H6"></path>
                            <circle cx="9" cy="20" r="1"></circle>
                            <circle cx="18" cy="20" r="1"></circle>
                        </svg>
                    </span>

                    <span class="cart-count">{{ $cartCount }}</span>
                </summary>

                <div class="mini-cart">

                    <h3>
                        <span data-i18n="cart-title" data-i18n-count="{{ $cartCount }}">Shopping Cart ({{ $cartCount }})</span>
                    </h3>

                    @if ($miniCartItems->isNotEmpty())

                        <div class="mini-cart-items">

                            @foreach ($miniCartItems as $item)

                                @php
                                    $product = $item->product;
                                    $variant = $item->variant;
                                    $image = $product->images->first();
                                    $unitPrice = $variant?->price ?? $product->price;
                                    $productNameArabic = data_get($product->translations, 'ar.name') ?: $product->name;
                                @endphp

                                <div class="mini-cart-item">

                                    @if ($image)
                                        <img
                                            src="{{ Storage::url($image->image_path) }}"
                                            alt="{{ $image->alt_text ?? $product->name }}"
                                            data-storefront-alt-en="{{ $image->alt_text ?? $product->name }}"
                                            data-storefront-alt-ar="{{ data_get($image->translations, 'ar.alt_text') ?: $productNameArabic }}"
                                        >
                                    @endif

                                    <div class="mini-cart-item-info">

                                        <a href="{{ route('products.show', $product->slug) }}">
                                            <span data-storefront-content-en="{{ $product->name }}" data-storefront-content-ar="{{ $productNameArabic }}">{{ $product->name }}</span>
                                        </a>

                                        <p><span data-i18n="quantity">Quantity</span>: {{ $item->quantity }}</p>

                                        @if ($variant?->size || $variant?->color)
                                            <p>
                                                @if ($variant->size || filled(data_get($variant->translations, 'ar.size')))
                                                            <span data-storefront-content-en="{{ $variant->size ?? '' }}" data-storefront-content-ar="{{ data_get($variant->translations, 'ar.size') ?: ($variant->size ?? '') }}">{{ $variant->size }}</span>
                                                @endif

                                                        @if ($variant->color || filled(data_get($variant->translations, 'ar.color')))
                                                            - <span data-storefront-content-en="{{ $variant->color ?? '' }}" data-storefront-content-ar="{{ data_get($variant->translations, 'ar.color') ?: ($variant->color ?? '') }}">{{ $variant->color }}</span>
                                                @endif
                                            </p>
                                        @endif

                                        <strong>
                                            EGP {{ number_format((float) $unitPrice * $item->quantity, 2) }}
                                        </strong>

                                    </div>
                                </div>

                            @endforeach

                        </div>

                        <div class="mini-cart-summary">
                            <span data-i18n="subtotal">Subtotal</span>

                            <strong>
                                EGP {{ number_format($miniCartSubtotal, 2) }}
                            </strong>
                        </div>

                        <div class="mini-cart-actions">

                            <a href="{{ route('cart') }}" class="mini-cart-view-all">
                                <span data-i18n="view-cart">View Cart</span>
                            </a>

                            <a href="{{ route('checkout') }}" class="mini-cart-checkout">
                                <span data-i18n="checkout">Checkout</span> - EGP {{ number_format($miniCartSubtotal, 2) }}
                            </a>

                        </div>

                    @else

                        <p data-i18n="cart-empty">Your shopping cart is empty.</p>

                        <a href="{{ route('products.index') }}" data-i18n="start-shopping">
                            Start Shopping
                        </a>

                    @endif

                </div>
            </details>

            {{-- Account Dropdown --}}
            <details class="account-dropdown">

                <summary class="account-trigger" aria-label="Account menu" data-i18n-aria="account-menu">

                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <circle cx="12" cy="8" r="4"></circle>
                        <path d="M4 21c0-4.4 3.6-7 8-7s8 2.6 8 7"></path>
                    </svg>

                    <span data-i18n="account">Account</span>

                    <svg class="account-chevron" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="m6 9 6 6 6-6"></path>
                    </svg>

                </summary>

                <div class="account-menu">

                    @guest

                        <div class="account-menu-heading">
                            <strong data-i18n="welcome">Welcome</strong>
                            <span data-i18n="sign-in-prompt">Sign in to your account</span>
                        </div>

                        <a href="{{ route('login') }}" class="account-menu-login">
                            <span data-i18n="login">Login</span>
                        </a>

                        <a href="{{ route('register') }}" class="account-menu-register">
                            <span data-i18n="create-account">Create Account</span>
                        </a>

                    @else

                        <div class="account-menu-heading">
                            <strong>{{ auth()->user()->name }}</strong>
                            <span data-i18n="manage-account">Manage your account</span>
                        </div>

                        <a href="{{ route('account') }}" class="account-menu-link">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <circle cx="12" cy="8" r="4"></circle>
                                <path d="M4 21c0-4.4 3.6-7 8-7s8 2.6 8 7"></path>
                            </svg>

                            <span data-i18n="my-account">My Account</span>
                        </a>

                        <div class="account-menu-divider"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <button type="submit" class="account-menu-logout">
                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M10 17l5-5-5-5"></path>
                                    <path d="M15 12H3"></path>
                                    <path d="M12 3h6a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-6"></path>
                                </svg>

                                <span data-i18n="logout">Logout</span>
                            </button>
                        </form>

                    @endguest

                </div>
            </details>

            <button
                type="button"
                class="header-icon language-toggle"
                data-language-toggle
                aria-label="{{ $storeLanguage === 'ar' ? 'تغيير اللغة' : 'Switch language' }}"
            >
                <span data-language-label>{{ $storeLanguage === 'ar' ? 'EN' : 'عربي' }}</span>
            </button>

            {{-- Color theme toggle --}}
            <button
                type="button"
                class="header-icon theme-toggle"
                data-theme-toggle
                aria-label="Switch to light mode"
                title="Switch to light mode"
            >
                <svg data-theme-icon="sun" viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="12" cy="12" r="4"></circle>
                    <path d="M12 2v2m0 16v2M4.93 4.93l1.42 1.42m11.3 11.3 1.42 1.42M2 12h2m16 0h2M4.93 19.07l1.42-1.42m11.3-11.3 1.42-1.42"></path>
                </svg>
                <svg data-theme-icon="moon" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M20.5 14.2A8.6 8.6 0 0 1 9.8 3.5 8.7 8.7 0 1 0 20.5 14.2Z"></path>
                </svg>
            </button>

            {{-- Mobile Menu Toggle --}}
            <button
                type="button"
                class="mobile-menu-toggle"
                id="mobileMenuToggle"
                aria-label="Open menu"
                aria-expanded="false"
                data-i18n-aria="open-menu"
            >
                <span></span>
                <span></span>
                <span></span>
            </button>

        </div>
    </div>
</header>
