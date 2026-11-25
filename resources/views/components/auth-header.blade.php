@props([
    'title',
    'description',
])

@php
    $titleEnglish = $attributes->get('data-auth-title-en');
    $titleArabic = $attributes->get('data-auth-title-ar');
    $descriptionEnglish = $attributes->get('data-auth-description-en');
    $descriptionArabic = $attributes->get('data-auth-description-ar');
@endphp

<div class="flex w-full flex-col gap-2 text-center">
    <span class="text-xs font-semibold uppercase tracking-[0.28em] text-amber-600 dark:text-amber-400">AK2 Technologies</span>
    <flux:heading size="xl" level="1" class="font-semibold tracking-tight">
        @if (filled($titleEnglish) && filled($titleArabic))
            <span data-auth-text-en="{{ $titleEnglish }}" data-auth-text-ar="{{ $titleArabic }}">{{ $title }}</span>
        @else
            {{ $title }}
        @endif
    </flux:heading>
    <flux:subheading class="leading-relaxed">
        @if (filled($descriptionEnglish) && filled($descriptionArabic))
            <span data-auth-text-en="{{ $descriptionEnglish }}" data-auth-text-ar="{{ $descriptionArabic }}">{{ $description }}</span>
        @else
            {{ $description }}
        @endif
    </flux:subheading>
</div>
