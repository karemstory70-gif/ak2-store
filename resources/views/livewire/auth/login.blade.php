@php
    $authLanguagePreference = request()->cookie('ak2-language');
    $authLanguage = in_array($authLanguagePreference, ['ar', 'en'], true)
        ? $authLanguagePreference
        : (str_starts_with(app()->getLocale(), 'ar') ? 'ar' : 'en');
    $isArabic = $authLanguage === 'ar';
@endphp

<x-layouts::auth :title="$isArabic ? 'تسجيل الدخول' : __('Log in')">
    <div class="flex flex-col gap-6 ak2-auth-form">
        <x-auth-header
            :title="$isArabic ? 'سجّل الدخول إلى حسابك' : __('Log in to your account')"
            :description="$isArabic ? 'أدخل بريدك الإلكتروني وكلمة المرور أدناه لتسجيل الدخول' : __('Enter your email and password below to log in')"
            data-auth-title-en="Log in to your account"
            data-auth-title-ar="سجّل الدخول إلى حسابك"
            data-auth-description-en="Enter your email and password below to log in"
            data-auth-description-ar="أدخل بريدك الإلكتروني وكلمة المرور أدناه لتسجيل الدخول"
        />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />


        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-6">
            @csrf

            <!-- Email Address -->
            <flux:input
                name="email"
                :label="$isArabic ? 'البريد الإلكتروني' : __('Email address')"
                :value="old('email')"
                type="email"
                required
                autofocus
                autocomplete="email"
                :placeholder="$isArabic ? 'أدخل بريدك الإلكتروني' : 'email@example.com'"
                data-auth-label-en="Email address"
                data-auth-label-ar="البريد الإلكتروني"
                data-auth-placeholder-en="email@example.com"
                data-auth-placeholder-ar="أدخل بريدك الإلكتروني"
            />

            <!-- Password -->
            <div class="relative">
                <flux:input
                    name="password"
                    :label="$isArabic ? 'كلمة المرور' : __('Password')"
                    type="password"
                    required
                    autocomplete="current-password"
                    :placeholder="$isArabic ? 'أدخل كلمة المرور' : __('Password')"
                    viewable
                    data-auth-label-en="Password"
                    data-auth-label-ar="كلمة المرور"
                    data-auth-placeholder-en="Password"
                    data-auth-placeholder-ar="أدخل كلمة المرور"
                />

                @if (Route::has('password.request'))
                    <flux:link class="absolute top-0 text-sm end-0" :href="route('password.request')" wire:navigate>
                        <span data-auth-text-en="Forgot your password?" data-auth-text-ar="هل نسيت كلمة المرور؟">
                            {{ $isArabic ? 'هل نسيت كلمة المرور؟' : __('Forgot your password?') }}
                        </span>
                    </flux:link>
                @endif
            </div>

            <!-- Remember Me -->
            <flux:checkbox
                name="remember"
                :label="$isArabic ? 'تذكرني' : __('Remember me')"
                :checked="old('remember')"
                data-auth-label-en="Remember me"
                data-auth-label-ar="تذكرني"
            />

            <div class="flex items-center justify-end">
                <flux:button variant="primary" type="submit" class="w-full" data-test="login-button">
                    <span data-auth-text-en="Log in" data-auth-text-ar="تسجيل الدخول">
                        {{ $isArabic ? 'تسجيل الدخول' : __('Log in') }}
                    </span>
                </flux:button>
            </div>
        </form>

    </div>
</x-layouts::auth>
