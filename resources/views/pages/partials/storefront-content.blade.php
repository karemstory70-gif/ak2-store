@php
    $storefrontText = static function ($english, $arabic = null): array {
        $english = (string) ($english ?? '');

        return [
            'en' => $english,
            'ar' => filled($arabic) ? (string) $arabic : $english,
        ];
    };
    $pageEyebrow = $page?->eyebrow ?? $defaults['eyebrow'];
    $pageHeading = $page?->heading ?? $defaults['heading'];
    $pageSubheading = $page?->subheading ?? $defaults['subheading'];
    $pageBody = $page?->body ?? $defaults['body'];
    $pageCtaText = $page?->primary_cta_text;
    $pageEyebrowText = $storefrontText(
        $pageEyebrow,
        filled($page?->eyebrow)
            ? data_get($page?->translations, 'ar.eyebrow')
            : data_get($defaults, 'translations.ar.eyebrow'),
    );
    $pageHeadingText = $storefrontText(
        $pageHeading,
        filled($page?->heading)
            ? data_get($page?->translations, 'ar.heading')
            : data_get($defaults, 'translations.ar.heading'),
    );
    $pageSubheadingText = $storefrontText(
        $pageSubheading,
        filled($page?->subheading)
            ? data_get($page?->translations, 'ar.subheading')
            : data_get($defaults, 'translations.ar.subheading'),
    );
    $pageBodyText = $storefrontText(
        $pageBody,
        data_get($page?->translations, 'ar.body') ?: data_get($defaults, 'translations.ar.body'),
    );
    $pageCtaText = $storefrontText($pageCtaText, data_get($page?->translations, 'ar.primary_cta_text'));
    $pageEyebrowText['ar'] = data_get($page?->translations, 'ar.eyebrow') ?: data_get($defaults, 'translations.ar.eyebrow') ?: $pageEyebrowText['en'];
    $pageHeadingText['ar'] = data_get($page?->translations, 'ar.heading') ?: data_get($defaults, 'translations.ar.heading') ?: $pageHeadingText['en'];
    $pageSubheadingText['ar'] = data_get($page?->translations, 'ar.subheading') ?: data_get($defaults, 'translations.ar.subheading') ?: $pageSubheadingText['en'];
    $pageBodyText['ar'] = data_get($page?->translations, 'ar.body') ?: data_get($defaults, 'translations.ar.body') ?: $pageBodyText['en'];
    $pageEyebrowVisible = filled($pageEyebrowText['en']) || filled(data_get($page?->translations, 'ar.eyebrow')) || filled(data_get($defaults, 'translations.ar.eyebrow'));
    $pageSubheadingVisible = filled($pageSubheadingText['en']) || filled(data_get($page?->translations, 'ar.subheading')) || filled(data_get($defaults, 'translations.ar.subheading'));
    $pageBodyVisible = filled($pageBodyText['en']) || filled(data_get($page?->translations, 'ar.body')) || filled(data_get($defaults, 'translations.ar.body'));
@endphp

<section class="ak2-content-page">
    <div class="ak2-content-page__glow" aria-hidden="true"></div>

    <div class="container ak2-content-page__container">
        @if ($page?->hero_image_path)
            <div class="ak2-content-page__image-wrap">
                <img
                    src="{{ Storage::disk('public')->url($page->hero_image_path) }}"
                    alt="{{ $page->heading ?? $page->title }}"
                    data-storefront-alt-en="{{ $page->heading ?? $page->title }}"
                    data-storefront-alt-ar="{{ data_get($page->translations, 'ar.heading') ?: data_get($page->translations, 'ar.title') ?: ($page->heading ?? $page->title) }}"
                    loading="eager"
                    class="ak2-content-page__image"
                >
            </div>
        @endif

        <header class="ak2-content-page__header">
            @if ($pageEyebrowVisible)
                <p class="ak2-content-page__eyebrow" data-storefront-content-en="{{ $pageEyebrowText['en'] }}" data-storefront-content-ar="{{ $pageEyebrowText['ar'] }}">{{ $pageEyebrowText['en'] }}</p>
            @endif

            <h1 data-storefront-content-en="{{ $pageHeadingText['en'] }}" data-storefront-content-ar="{{ $pageHeadingText['ar'] }}">{{ $pageHeadingText['en'] }}</h1>

            @if ($pageSubheadingVisible)
                <p class="ak2-content-page__lead" data-storefront-content-en="{{ $pageSubheadingText['en'] }}" data-storefront-content-ar="{{ $pageSubheadingText['ar'] }}">{{ $pageSubheadingText['en'] }}</p>
            @endif

            @if ($pageBodyVisible)
                <div class="ak2-content-page__body" data-storefront-content-en="{{ $pageBodyText['en'] }}" data-storefront-content-ar="{{ $pageBodyText['ar'] }}">{{ $pageBodyText['en'] }}</div>
            @endif

            @if (($page?->primary_cta_text || filled(data_get($page?->translations, 'ar.primary_cta_text'))) && $page?->primary_cta_url)
                <a class="ak2-content-page__button" href="{{ $page->primary_cta_url }}">
                    <span data-storefront-content-en="{{ $pageCtaText['en'] }}" data-storefront-content-ar="{{ $pageCtaText['ar'] }}">{{ $pageCtaText['en'] }}</span>
                </a>
            @endif
        </header>

        @foreach ($sections as $section)
            <article class="ak2-content-page__section">
                @if (! empty($section['image_path']))
                    <img
                        src="{{ Storage::disk('public')->url($section['image_path']) }}"
                        alt="{{ $section['heading'] ?? $page?->title ?? $defaults['heading'] }}"
                        data-storefront-alt-en="{{ $section['heading'] ?? $page?->title ?? $defaults['heading'] }}"
                        data-storefront-alt-ar="{{ data_get($section, 'translations.ar.heading') ?: data_get($page?->translations, 'ar.title') ?: ($section['heading'] ?? $page?->title ?? $defaults['heading']) }}"
                        loading="lazy"
                        class="ak2-content-page__section-image"
                    >
                @endif

                @if (! empty($section['eyebrow']) || filled(data_get($section, 'translations.ar.eyebrow')))
                    <p class="ak2-content-page__eyebrow" data-storefront-content-en="{{ $section['eyebrow'] ?? '' }}" data-storefront-content-ar="{{ data_get($section, 'translations.ar.eyebrow') ?: ($section['eyebrow'] ?? '') }}">{{ $section['eyebrow'] ?? '' }}</p>
                @endif

                @if (! empty($section['heading']) || filled(data_get($section, 'translations.ar.heading')))
                    <h2 data-storefront-content-en="{{ $section['heading'] ?? '' }}" data-storefront-content-ar="{{ data_get($section, 'translations.ar.heading') ?: ($section['heading'] ?? '') }}">{{ $section['heading'] ?? '' }}</h2>
                @endif

                @if (! empty($section['body']) || filled(data_get($section, 'translations.ar.body')))
                    <div class="ak2-content-page__body" data-storefront-content-en="{{ $section['body'] ?? '' }}" data-storefront-content-ar="{{ data_get($section, 'translations.ar.body') ?: ($section['body'] ?? '') }}">{{ $section['body'] ?? '' }}</div>
                @endif

                @if ((! empty($section['cta_text']) || filled(data_get($section, 'translations.ar.cta_text'))) && ! empty($section['cta_url']))
                    <a class="ak2-content-page__button" href="{{ $section['cta_url'] }}">
                        <span data-storefront-content-en="{{ $section['cta_text'] ?? '' }}" data-storefront-content-ar="{{ data_get($section, 'translations.ar.cta_text') ?: ($section['cta_text'] ?? '') }}">{{ $section['cta_text'] ?? '' }}</span>
                    </a>
                @endif
            </article>
        @endforeach
    </div>
</section>
