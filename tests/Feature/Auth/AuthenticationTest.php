<?php

use App\Models\User;

test('login screen can be rendered', function () {
    $response = $this->get(route('login'));

    $response
        ->assertOk()
        ->assertSee('assets/css/auth.css', false)
        ->assertSee('assets/js/auth-language.js', false)
        ->assertSee('ak2-auth-form', false);
});

test('register screen uses the AK2 auth presentation', function () {
    $response = $this->get(route('register'));

    $response
        ->assertOk()
        ->assertSee('assets/css/auth.css', false)
        ->assertSee('assets/js/auth-language.js', false)
        ->assertSee('ak2-auth-form', false);
});

test('auth screens default to english and left-to-right without a saved preference', function () {
    $this->get(route('login'))
        ->assertOk()
        ->assertSee('lang="en"', false)
        ->assertSee('dir="ltr"', false)
        ->assertSee('Log in to your account');

    $this->get(route('register'))
        ->assertOk()
        ->assertSee('lang="en"', false)
        ->assertSee('dir="ltr"', false)
        ->assertSee('Create your account');
});

test('login screen renders arabic labels and rtl when arabic is selected', function () {
    $this->withCookie('ak2-language', 'ar')
        ->get(route('login'))
        ->assertOk()
        ->assertSee('lang="ar"', false)
        ->assertSee('dir="rtl"', false)
        ->assertSee('سجّل الدخول إلى حسابك', false)
        ->assertSee('data-auth-label-ar="البريد الإلكتروني"', false)
        ->assertSee('data-auth-placeholder-ar="أدخل بريدك الإلكتروني"', false);
});

test('register screen renders arabic copy when arabic is selected', function () {
    $this->withCookie('ak2-language', 'ar')
        ->get(route('register'))
        ->assertOk()
        ->assertSee('lang="ar"', false)
        ->assertSee('dir="rtl"', false)
        ->assertSee('أنشئ حسابك', false)
        ->assertSee('data-auth-label-ar="الاسم الكامل"', false)
        ->assertSee('data-auth-label-ar="تأكيد كلمة المرور"', false)
        ->assertSee('لديك حساب بالفعل؟', false);
});

test('auth screens use rtl direction for the arabic locale', function () {
    app()->setLocale('ar');

    $this->get(route('login'))
        ->assertOk()
        ->assertSee('<html', false)
        ->assertSee('lang="ar"', false)
        ->assertSee('dir="rtl"', false)
        ->assertSee('class="dark"', false);
});

test('users can authenticate using the login screen', function () {
    $user = User::factory()->create();

    $response = $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('dashboard', absolute: false));

    $this->assertAuthenticated();
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $response = $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $response->assertSessionHasErrorsIn('email');

    $this->assertGuest();
});

test('users can logout', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('logout'));

    $response->assertRedirect(route('home'));

    $this->assertGuest();
});
