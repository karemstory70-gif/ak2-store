<?php
use App\Http\Controllers\AccountController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\StorefrontPageController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;

// Main Store Pages
Route::get('/', HomeController::class)->name('home');

Route::get('/products', [ProductController::class, 'index'])
    ->name('products.index');
    
Route::get('/categories', [CategoryController::class, 'index'])
    ->name('categories.index');

Route::get('/about', [StorefrontPageController::class, 'about'])
    ->name('about');

// Product Pages
Route::get('/products/{slug}', [ProductController::class, 'show'])
    ->name('products.show');

// Shopping Pages
// Shopping Pages
Route::middleware('auth')->group(function () {

    Route::get('/cart', [CartController::class, 'index'])
        ->name('cart');

    Route::post('/cart/items', [CartController::class, 'add'])
        ->name('cart.add');

    Route::patch('/cart/items/{itemId}', [CartController::class, 'update'])
        ->name('cart.update');

    Route::delete('/cart/items/{itemId}', [CartController::class, 'remove'])
    ->name('cart.remove');

});

Route::middleware('auth')->group(function () {

    Route::get('/checkout', [CheckoutController::class, 'index'])
        ->name('checkout');

    Route::post('/checkout', [CheckoutController::class, 'store'])
        ->name('checkout.store');

});

// Other Pages
Route::get('/contact', [StorefrontPageController::class, 'contact'])
    ->name('contact');

Route::get('/pages/{slug}', [StorefrontPageController::class, 'page'])
    ->where('slug', '[a-z0-9]+(?:-[a-z0-9]+)*')
    ->name('pages.show');

// Existing Account Settings
require __DIR__.'/settings.php';
// Customer Account
Route::middleware('auth')->group(function () {

    Route::get('/account', [AccountController::class, 'show'])
        ->name('account');

    Route::patch('/account/profile', [AccountController::class, 'updateProfile'])
        ->name('account.profile.update');

    Route::post('/account/avatar', [AccountController::class, 'updateAvatar'])
        ->name('account.avatar.update');

    Route::delete('/account/avatar', [AccountController::class, 'deleteAvatar'])
        ->name('account.avatar.delete');

});
// Admin Dashboard
Route::middleware(['auth', 'admin'])
    ->prefix('legacy-admin')
    ->name('legacy-admin.')
    ->group(function () {

        Route::get('/', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/orders', [OrderController::class, 'index'])
            ->name('orders.index');

        Route::get('/orders/{order}', [OrderController::class, 'show'])
            ->name('orders.show');

        Route::patch('/orders/{order}', [OrderController::class, 'update'])
            ->name('orders.update');

        Route::get('/products', [AdminProductController::class, 'index'])
            ->name('products.index');

        Route::get('/products/create', [AdminProductController::class, 'create'])
            ->name('products.create');

        Route::post('/products', [AdminProductController::class, 'store'])
            ->name('products.store');
    });
