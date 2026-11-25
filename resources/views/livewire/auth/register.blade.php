@php
    $authLanguagePreference = request()->cookie('ak2-language');
    $authLanguage = in_array($authLanguagePreference, ['ar', 'en'], true)
        ? $authLanguagePreference
        : (str_starts_with(app()->getLocale(), 'ar') ? 'ar' : 'en');
    $isArabic = $authLanguage === 'ar';
@endphp

<x-layouts::auth :title="$isArabic ? 'إنشاء حساب' : __('Create account')">
    <div class="flex flex-col gap-6 ak2-auth-form">

        <x-auth-header
            :title="$isArabic ? 'أنشئ حسابك' : __('Create your account')"
            :description="$isArabic ? 'أدخل بياناتك أدناه لإنشاء حسابك' : __('Enter your details below to create your account')"
            data-auth-title-en="Create your account"
            data-auth-title-ar="أنشئ حسابك"
            data-auth-description-en="Enter your details below to create your account"
            data-auth-description-ar="أدخل بياناتك أدناه لإنشاء حسابك"
        />

        <form method="POST" action="{{ route('register.store') }}" class="flex flex-col gap-6">
            @csrf

            <!-- Name -->
            <flux:input
                name="name"
                :label="$isArabic ? 'الاسم الكامل' : __('Full name')"
                :value="old('name')"
                type="text"
                required
                autofocus
                autocomplete="name"
                :placeholder="$isArabic ? 'اكتب اسمك الكامل' : 'Your full name'"
                data-auth-label-en="Full name"
                data-auth-label-ar="الاسم الكامل"
                data-auth-placeholder-en="Your full name"
                data-auth-placeholder-ar="اكتب اسمك الكامل"
            />

            <!-- Email -->
            <flux:input
                name="email"
                :label="$isArabic ? 'البريد الإلكتروني' : __('Email address')"
                :value="old('email')"
                type="email"
                required
                autocomplete="email"
                :placeholder="$isArabic ? 'أدخل بريدك الإلكتروني' : 'email@example.com'"
                data-auth-label-en="Email address"
                data-auth-label-ar="البريد الإلكتروني"
                data-auth-placeholder-en="email@example.com"
                data-auth-placeholder-ar="أدخل بريدك الإلكتروني"
            />

            <!-- Password -->
            <flux:input
                name="password"
                :label="$isArabic ? 'كلمة المرور' : __('Password')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="$isArabic ? 'أنشئ كلمة مرور' : 'Create a password'"
                viewable
                data-auth-label-en="Password"
                data-auth-label-ar="كلمة المرور"
                data-auth-placeholder-en="Create a password"
                data-auth-placeholder-ar="أنشئ كلمة مرور"
            />

            <!-- Confirm Password -->
            <flux:input
                name="password_confirmation"
                :label="$isArabic ? 'تأكيد كلمة المرور' : __('Confirm password')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="$isArabic ? 'أعد كتابة كلمة المرور' : 'Confirm your password'"
                viewable
                data-auth-label-en="Confirm password"
                data-auth-label-ar="تأكيد كلمة المرور"
                data-auth-placeholder-en="Confirm your password"
                data-auth-placeholder-ar="أعد كتابة كلمة المرور"
            />

            <!-- Submit -->
            <flux:button variant="primary" type="submit" class="w-full">
                <span data-auth-text-en="Create account" data-auth-text-ar="إنشاء حساب">
                    {{ $isArabic ? 'إنشاء حساب' : __('Create account') }}
                </span>
            </flux:button>

        </form>

        <div class="text-center text-sm">
            <span data-auth-text-en="Already have an account?" data-auth-text-ar="لديك حساب بالفعل؟">
                {{ $isArabic ? 'لديك حساب بالفعل؟' : 'Already have an account?' }}
            </span>
            <flux:link :href="route('login')" wire:navigate>
                <span data-auth-text-en="Log in" data-auth-text-ar="تسجيل الدخول">
                    {{ $isArabic ? 'تسجيل الدخول' : 'Log in' }}
                </span>
            </flux:link>
        </div>

    </div>
</x-layouts::auth>
