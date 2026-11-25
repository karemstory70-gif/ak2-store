
@php
    $footerSection = $globalSections['footer'] ?? [];
    $footerLinks = collect($footerSection['links'] ?? [])->groupBy('group');
@endphp

<footer class="site-footer">

    <div class="container">

        <div class="footer-main">

            <!-- Brand Information -->
            <div class="footer-brand">
                <a href="{{ route('home') }}" class="footer-logo">
                    <span data-storefront-content-en="{{ $footerSection['heading'] ?? 'AK2' }}" data-storefront-content-ar="{{ data_get($footerSection, 'translations.ar.heading') ?: ($footerSection['heading'] ?? 'AK2') }}">{{ $footerSection['heading'] ?? 'AK2' }}</span>
                </a>

                @if (isset($footerSection['body']))
                    <p data-storefront-content-en="{{ $footerSection['body'] }}" data-storefront-content-ar="{{ data_get($footerSection, 'translations.ar.body') ?: $footerSection['body'] }}">{{ $footerSection['body'] }}</p>
                @else
                    <p data-i18n="footer-description">Discover your identity through fashion. Quality, confidence and style in every detail.</p>
                @endif

                <div class="footer-socials">
                    @forelse ($footerLinks->get('footer_social', collect()) as $link)
                        <a href="{{ $link['url'] }}" aria-label="{{ $link['label'] }}" data-storefront-aria-label-en="{{ $link['label'] }}" data-storefront-aria-label-ar="{{ data_get($link, 'translations.ar.label') ?: $link['label'] }}" target="_blank" rel="noopener noreferrer" data-storefront-content-en="{{ $link['label'] }}" data-storefront-content-ar="{{ data_get($link, 'translations.ar.label') ?: $link['label'] }}">{{ $link['label'] }}</a>
                    @empty
                        <a href="#" aria-label="Instagram" data-i18n-aria="instagram">IG</a>
                        <a href="#" aria-label="Facebook" data-i18n-aria="facebook">FB</a>
                        <a href="#" aria-label="TikTok" data-i18n-aria="tiktok">TK</a>
                    @endforelse
                </div>
            </div>

            <!-- Quick Links -->
            <div class="footer-column">
                <h3 data-i18n="explore">Explore</h3>
                @forelse ($footerLinks->get('footer_explore', collect()) as $link)
                    <a href="{{ $link['url'] }}" data-storefront-content-en="{{ $link['label'] }}" data-storefront-content-ar="{{ data_get($link, 'translations.ar.label') ?: $link['label'] }}">{{ $link['label'] }}</a>
                @empty
                    <a href="{{ route('home') }}" data-i18n="nav-home">Home</a>
                    <a href="{{ route('products.index') }}" data-i18n="nav-shop">Shop</a>
                    <a href="{{ route('categories.index') }}" data-i18n="nav-categories">Categories</a>
                    <a href="{{ route('about') }}" data-i18n="nav-about">About Us</a>
                @endforelse
            </div>

            <!-- Customer Service -->
            <div class="footer-column">
                <h3 data-i18n="customer-service">Customer Service</h3>
                @forelse ($footerLinks->get('footer_service', collect()) as $link)
                    <a href="{{ $link['url'] }}" data-storefront-content-en="{{ $link['label'] }}" data-storefront-content-ar="{{ data_get($link, 'translations.ar.label') ?: $link['label'] }}">{{ $link['label'] }}</a>
                @empty
                    <a href="{{ route('contact') }}" data-i18n="contact-us">Contact Us</a>
                    <a href="{{ route('cart') }}" data-i18n="shopping-cart">Shopping Cart</a>
                    <a href="{{ route('checkout') }}" data-i18n="checkout">Checkout</a>
                @endforelse
            </div>

            <!-- Contact -->
            <div class="footer-column footer-contact">
                <h3 data-i18n="get-in-touch">Get In Touch</h3>
                @forelse ($footerLinks->get('footer_contact', collect()) as $link)
                    <a href="{{ $link['url'] }}" data-storefront-content-en="{{ $link['label'] }}" data-storefront-content-ar="{{ data_get($link, 'translations.ar.label') ?: $link['label'] }}">{{ $link['label'] }}</a>
                @empty
                    <p data-i18n="footer-email">Email: support@ak2.com</p>
                    <p data-i18n="footer-location">Location: Egypt</p>
                    <p data-i18n="footer-help">We are here to help you.</p>
                @endforelse
            </div>

        </div>

        <div class="footer-bottom">

            <p>
                © {{ date('Y') }}
                @if (isset($footerSection['fine_print']))
                    <span data-storefront-content-en="{{ $footerSection['fine_print'] }}" data-storefront-content-ar="{{ data_get($footerSection, 'translations.ar.fine_print') ?: $footerSection['fine_print'] }}">{{ $footerSection['fine_print'] }}</span>
                @else
                    <span data-i18n="footer-copyright">AK2 Fashion Store. All Rights Reserved.</span>
                @endif
            </p>

            @if (isset($footerSection['secondary_text']))
                <p data-storefront-content-en="{{ $footerSection['secondary_text'] }}" data-storefront-content-ar="{{ data_get($footerSection, 'translations.ar.secondary_text') ?: $footerSection['secondary_text'] }}">{{ $footerSection['secondary_text'] }}</p>
            @else
                <p data-i18n="footer-secondary">Designed with Purpose. Built for Style.</p>
            @endif

        </div>

    </div>

</footer>
