@php
    $isAk2AuthPage = request()->routeIs('login', 'register');
    $authLanguagePreference = request()->cookie('ak2-language');
    $authLanguage = $isAk2AuthPage && in_array($authLanguagePreference, ['ar', 'en'], true)
        ? $authLanguagePreference
        : (str_starts_with(app()->getLocale(), 'ar') ? 'ar' : 'en');
    $authTitleEnglish = request()->routeIs('login') ? 'Log in' : 'Create account';
    $authTitleArabic = request()->routeIs('login') ? 'تسجيل الدخول' : 'إنشاء حساب';
@endphp

<!DOCTYPE html>
<html
    lang="{{ $authLanguage }}"
    dir="{{ $authLanguage === 'ar' ? 'rtl' : 'ltr' }}"
    class="dark"
    @if ($isAk2AuthPage)
        data-auth-title-en="{{ $authTitleEnglish }} - {{ config('app.name', 'Laravel') }}"
        data-auth-title-ar="{{ $authTitleArabic }} - {{ config('app.name', 'Laravel') }}"
    @endif
>
    <head>
        @include('partials.head')
        @if ($isAk2AuthPage)
            <link rel="stylesheet" href="{{ asset('assets/css/auth.css') }}">
            <script src="{{ asset('assets/js/auth-language.js') }}" defer></script>
        @endif
    </head>
    <body class="{{ $isAk2AuthPage ? 'ak2-auth-page' : 'min-h-screen bg-white antialiased dark:bg-linear-to-b dark:from-neutral-950 dark:to-neutral-900' }}">
        <div class="bg-background flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10 {{ $isAk2AuthPage ? 'ak2-auth-stage' : '' }}">
            @if ($isAk2AuthPage)
                <button
                    type="button"
                    class="ak2-auth-language-toggle"
                    data-auth-language-toggle
                    aria-label="{{ $authLanguage === 'ar' ? 'التبديل إلى الإنجليزية' : 'Switch language to Arabic' }}"
                    title="{{ $authLanguage === 'ar' ? 'التبديل إلى الإنجليزية' : 'Switch language to Arabic' }}"
                >
                    <span data-auth-language-label>{{ $authLanguage === 'ar' ? 'EN' : 'عربي' }}</span>
                </button>
            @endif
            <div class="flex w-full max-w-sm flex-col gap-2 {{ $isAk2AuthPage ? 'ak2-auth-shell' : '' }}">
                <a
                    href="{{ route('home') }}"
                    class="flex flex-col items-center gap-2 font-medium {{ $isAk2AuthPage ? 'ak2-auth-logo' : '' }}"
                    aria-label="{{ $authLanguage === 'ar' ? 'تقنيات AK2' : 'AK2 Technologies' }}"
                    wire:navigate
                >
                    <x-app-logo-icon
                        class="h-10 w-auto max-w-[12rem]"
                        :alt="$authLanguage === 'ar' ? 'تقنيات AK2' : 'AK2 Technologies'"
                    />
                    <span class="sr-only">{{ $authLanguage === 'ar' ? 'تقنيات AK2' : 'AK2 Technologies' }}</span>
                </a>
                <div class="flex flex-col gap-6">
                    {{ $slot }}
                </div>
            </div>
        </div>

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
