
<div class="announcement-bar">
    <div class="container announcement-container">

        <p class="announcement-message">
            <span class="announcement-star">✦</span>
            @php
                $announcementText = $globalSections['announcement']['body'] ?? 'WELCOME TO AK2 — FREE SHIPPING ON ORDERS OVER EGP 2,000';
                $announcementArabic = data_get($globalSections['announcement'] ?? [], 'translations.ar.body')
                    ?: (isset($globalSections['announcement']['body'])
                        ? $announcementText
                        : 'مرحباً بكم في AK2 — شحن مجاني للطلبات التي تزيد قيمتها على 2,000 جنيه');
            @endphp
            <span data-storefront-content-en="{{ $announcementText }}" data-storefront-content-ar="{{ $announcementArabic }}">{{ $announcementText }}</span>
            <span class="announcement-star">✦</span>
        </p>

    </div>
</div>
