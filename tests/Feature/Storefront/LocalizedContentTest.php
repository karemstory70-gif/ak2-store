<?php

use App\Models\Banner;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\StorefrontPage;
use App\Models\User;

it('renders translated categories, products, image text, and variant options', function () {
    $category = Category::create([
        'name' => 'Footwear',
        'slug' => 'translated-footwear',
        'description' => 'English category description',
        'translations' => [
            'ar' => [
                'name' => 'أحذية',
                'description' => 'وصف التصنيف',
            ],
        ],
    ]);

    Category::create([
        'parent_id' => $category->id,
        'name' => 'Running Shoes',
        'slug' => 'translated-running-shoes',
        'translations' => [
            'ar' => [
                'name' => 'أحذية رياضية',
            ],
        ],
    ]);

    $product = Product::create([
        'category_id' => $category->id,
        'name' => 'Classic Shoe',
        'slug' => 'translated-classic-shoe',
        'sku' => 'AK2-LOCALIZED-PRODUCT-001',
        'description' => 'English product description',
        'price' => '100.00',
        'stock_quantity' => 5,
        'is_active' => true,
        'is_featured' => true,
        'published_at' => now(),
        'translations' => [
            'ar' => [
                'name' => 'حذاء كلاسيكي',
                'description' => 'وصف المنتج',
            ],
        ],
    ]);

    ProductImage::create([
        'product_id' => $product->id,
        'image_path' => 'products/classic-shoe.jpg',
        'alt_text' => 'Classic shoe',
        'is_primary' => true,
        'translations' => [
            'ar' => [
                'alt_text' => 'حذاء كلاسيكي من الجانب',
            ],
        ],
    ]);

    ProductVariant::create([
        'product_id' => $product->id,
        'sku' => 'AK2-LOCALIZED-PRODUCT-001-L',
        'size' => 'Large',
        'color' => 'Navy',
        'price' => '125.00',
        'stock_quantity' => 3,
        'is_active' => true,
        'translations' => [
            'ar' => [
                'size' => 'كبير',
                'color' => 'كحلي',
            ],
        ],
    ]);

    $this->get(route('categories.index'))
        ->assertOk()
        ->assertSee('data-storefront-content-ar="أحذية"', false)
        ->assertSee('data-storefront-content-ar="وصف التصنيف"', false)
        ->assertSee('data-storefront-content-ar="أحذية رياضية"', false);

    $this->get(route('products.index'))
        ->assertOk()
        ->assertSee('data-storefront-content-ar="حذاء كلاسيكي"', false)
        ->assertSee('data-storefront-alt-ar="حذاء كلاسيكي من الجانب"', false)
        ->assertSee('EGP 100.00', false);

    $this->get(route('products.show', $product->slug))
        ->assertOk()
        ->assertSee('data-storefront-content-ar="وصف المنتج"', false)
        ->assertSee('data-storefront-size-ar="كبير"', false)
        ->assertSee('data-storefront-color-ar=" - كحلي"', false)
        ->assertSee('EGP 125.00', false);
});

it('renders the home hero and promotion translations from their existing sources', function () {
    $homePage = StorefrontPage::create([
        'slug' => 'home',
        'title' => 'Home',
        'is_published' => true,
        'sections' => [
            [
                'key' => 'promotion',
                'eyebrow' => 'AK2 STYLE EDIT',
                'heading' => 'ELEVATE YOUR EVERYDAY STYLE.',
                'body' => 'Discover a collection that reflects your style.',
                'cta_text' => 'SHOP THE COLLECTION',
                'cta_url' => '/products?source=everyday-style',
                'image_path' => 'banners/everyday-style.jpg',
                'translations' => [
                    'ar' => [
                        'eyebrow' => 'أسلوب AK2',
                        'heading' => 'ارتقِ بأناقتك اليومية.',
                        'body' => 'اكتشف مجموعة تعبر عن أسلوبك.',
                        'cta_text' => 'تسوق المجموعة',
                    ],
                ],
                'is_active' => true,
            ],
        ],
    ]);

    Banner::create([
        'title' => 'Hero banner',
        'subtitle' => 'Hero description',
        'image_path' => 'banners/hero.jpg',
        'placement' => 'homepage_hero',
        'is_active' => true,
        'sort_order' => 1,
        'translations' => [
            'ar' => [
                'title' => 'بانر الواجهة',
                'subtitle' => 'وصف الواجهة',
            ],
        ],
    ]);

    Banner::create([
        'title' => 'Promotion banner',
        'subtitle' => 'Promotion description',
        'image_path' => 'banners/promotion.jpg',
        'placement' => 'homepage_promotion',
        'is_active' => true,
        'sort_order' => 1,
        'button_text' => 'Shop now',
        'button_url' => '/products',
        'translations' => [
            'ar' => [
                'title' => 'بانر العرض',
                'subtitle' => 'وصف العرض',
                'button_text' => 'تسوق الآن',
            ],
        ],
    ]);

    $category = Category::create([
        'name' => 'Accessories',
        'slug' => 'homepage-accessories',
        'translations' => [
            'ar' => [
                'name' => 'إكسسوارات',
            ],
        ],
    ]);

    $product = Product::create([
        'category_id' => $category->id,
        'name' => 'Leather Wallet',
        'slug' => 'homepage-leather-wallet',
        'sku' => 'AK2-HOMEPAGE-LOCALIZED-001',
        'price' => '80.00',
        'stock_quantity' => 4,
        'is_active' => true,
        'is_featured' => true,
        'published_at' => now(),
        'translations' => [
            'ar' => [
                'name' => 'محفظة جلدية',
            ],
        ],
    ]);

    ProductImage::create([
        'product_id' => $product->id,
        'image_path' => 'products/leather-wallet.jpg',
        'alt_text' => 'Leather wallet',
        'is_primary' => true,
        'translations' => [
            'ar' => [
                'alt_text' => 'محفظة جلدية أنيقة',
            ],
        ],
    ]);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('data-storefront-content-ar="بانر الواجهة"', false)
        ->assertSee('data-storefront-content-en="ELEVATE YOUR EVERYDAY STYLE."', false)
        ->assertSee('data-storefront-content-ar="ارتقِ بأناقتك اليومية."', false)
        ->assertSee('data-storefront-content-ar="أسلوب AK2"', false)
        ->assertSee('data-storefront-content-ar="اكتشف مجموعة تعبر عن أسلوبك."', false)
        ->assertSee('data-storefront-content-ar="تسوق المجموعة"', false)
        ->assertSee('href="/products?source=everyday-style" class="promotion-button"', false)
        ->assertSee('class="promotion-banner has-background-image"', false)
        ->assertSee('class="promotion-background-image"', false)
        ->assertSee('/storage/banners/everyday-style.jpg', false)
        ->assertSee('data-storefront-content-ar="تسوق حسب التصنيف"', false)
        ->assertSee('data-storefront-content-ar="محفظة جلدية"', false)
        ->assertSee('data-storefront-alt-ar="محفظة جلدية أنيقة"', false)
        ->assertDontSee('data-storefront-content-ar="بانر العرض"', false)
        ->assertDontSee('data-storefront-content-ar="وصف العرض"', false)
        ->assertDontSee('data-storefront-content-ar="تسوق الآن"', false)
        ->assertDontSee('href="' . route('pages.show', ['slug' => 'home']) . '"', false);

    $sections = $homePage->sections;
    $sections[0]['is_active'] = false;
    $homePage->update(['sections' => $sections]);

    $this->get(route('home'))
        ->assertOk()
        ->assertDontSee('class="promotion-section"', false);

    $this->get(route('pages.show', ['slug' => 'home']))
        ->assertNotFound();
});

it('keeps the promotion banner gradient when no image is configured', function () {
    StorefrontPage::create([
        'slug' => 'home',
        'title' => 'Home',
        'is_published' => true,
        'sections' => [
            [
                'key' => 'promotion',
                'heading' => 'ELEVATE YOUR EVERYDAY STYLE.',
                'is_active' => true,
            ],
        ],
    ]);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('class="promotion-banner"', false)
        ->assertDontSee('class="promotion-banner has-background-image"', false)
        ->assertDontSee('class="promotion-background-image"', false);
});

it('renders translated CMS content and global navigation, announcement, and footer copy', function () {
    StorefrontPage::create([
        'slug' => 'global',
        'title' => 'Global content',
        'is_published' => true,
        'sections' => [
            [
                'key' => 'announcement',
                'body' => 'English announcement',
                'translations' => [
                    'ar' => [
                        'body' => 'إعلان المتجر',
                    ],
                ],
                'is_active' => true,
            ],
            [
                'key' => 'navigation',
                'links' => [
                    [
                        'group' => 'navigation',
                        'label' => 'Support',
                        'url' => '/contact',
                        'translations' => [
                            'ar' => [
                                'label' => 'الدعم',
                            ],
                        ],
                    ],
                ],
                'is_active' => true,
            ],
            [
                'key' => 'footer',
                'heading' => 'AK2 Footer',
                'body' => 'English footer copy',
                'translations' => [
                    'ar' => [
                        'heading' => 'تذييل AK2',
                        'body' => 'محتوى التذييل',
                    ],
                ],
                'is_active' => true,
                'links' => [],
            ],
        ],
    ]);

    StorefrontPage::create([
        'slug' => 'localized-story',
        'title' => 'Our story',
        'heading' => 'About AK2',
        'body' => 'Our English story',
        'is_published' => true,
        'translations' => [
            'ar' => [
                'title' => 'قصتنا',
                'meta_title' => 'قصتنا | AK2',
                'meta_description' => 'تعرف على قصة AK2.',
                'heading' => 'عن AK2',
                'body' => 'قصتنا باللغة العربية',
            ],
        ],
        'sections' => [
            [
                'key' => 'content',
                'heading' => 'Our collection',
                'body' => 'English section copy',
                'translations' => [
                    'ar' => [
                        'heading' => 'مجموعتنا',
                        'body' => 'محتوى القسم بالعربية',
                    ],
                ],
                'is_active' => true,
            ],
        ],
    ]);

    $this->get(route('pages.show', 'localized-story'))
        ->assertOk()
        ->assertSee('data-storefront-meta-title-ar="قصتنا | AK2"', false)
        ->assertSee('data-storefront-meta-description-ar="تعرف على قصة AK2."', false)
        ->assertSee('data-storefront-content-ar="قصتنا باللغة العربية"', false)
        ->assertSee('data-storefront-content-ar="محتوى القسم بالعربية"', false)
        ->assertSee('data-storefront-content-ar="إعلان المتجر"', false)
        ->assertSee('data-storefront-content-ar="الدعم"', false)
        ->assertSee('data-storefront-content-ar="محتوى التذييل"', false);

    $this->withCookie('ak2-language', 'ar')
        ->get(route('pages.show', 'localized-story'))
        ->assertOk()
        ->assertSee('lang="ar" dir="rtl"', false)
        ->assertSee('content="تعرف على قصة AK2."', false);

    $this->get(route('about'))
        ->assertOk()
        ->assertSee('data-storefront-content-ar="عن AK2"', false);

    $this->get(route('contact'))
        ->assertOk()
        ->assertSee('data-storefront-content-ar="تواصل مع AK2"', false);
});

it('renders translated product and variant names in the authenticated cart and checkout', function () {
    $category = Category::create([
        'name' => 'Accessories',
        'slug' => 'localized-accessories',
    ]);

    $product = Product::create([
        'category_id' => $category->id,
        'name' => 'Travel Bag',
        'slug' => 'localized-travel-bag',
        'sku' => 'AK2-LOCALIZED-CART-001',
        'price' => '100.00',
        'stock_quantity' => 0,
        'is_active' => true,
        'published_at' => now(),
        'translations' => [
            'ar' => [
                'name' => 'حقيبة سفر',
            ],
        ],
    ]);

    $variant = ProductVariant::create([
        'product_id' => $product->id,
        'sku' => 'AK2-LOCALIZED-CART-001-NAVY',
        'size' => 'Large',
        'color' => 'Navy',
        'price' => '125.00',
        'stock_quantity' => 3,
        'is_active' => true,
        'translations' => [
            'ar' => [
                'size' => 'كبير',
                'color' => 'كحلي',
            ],
        ],
    ]);

    $user = User::factory()->create();
    $cart = Cart::create(['user_id' => $user->id]);
    $cart->items()->create([
        'product_id' => $product->id,
        'product_variant_id' => $variant->id,
        'quantity' => 1,
    ]);

    $this->actingAs($user)
        ->get(route('cart'))
        ->assertOk()
        ->assertSee('data-storefront-content-ar="حقيبة سفر"', false)
        ->assertSee('data-storefront-content-ar="كبير"', false)
        ->assertSee('data-storefront-content-ar="كحلي"', false)
        ->assertSee('125.00', false);

    $this->actingAs($user)
        ->get(route('checkout'))
        ->assertOk()
        ->assertSee('name="customer_phone"', false)
        ->assertSee('data-storefront-content-ar="حقيبة سفر"', false)
        ->assertSee('data-storefront-content-ar="كبير"', false)
        ->assertSee('data-storefront-content-ar="كحلي"', false)
        ->assertSee('125.00 EGP', false);
});

it('renders the session order confirmation with a localized message and the products link', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->withCookie('ak2-language', 'ar')
        ->withSession([
            'order_number' => 'AK2-20260318-ABC123',
            'success' => 'Your order has been placed successfully.',
        ])
        ->get(route('checkout'))
        ->assertOk()
        ->assertSee('lang="ar" dir="rtl"', false)
        ->assertSee('aria-labelledby="checkout-success-title"', false)
        ->assertSee('id="checkout-success-title" data-i18n="order-success-title"', false)
        ->assertSee(
            '<p class="checkout-success-message" data-i18n="order-success-message">Your order has been placed successfully.</p>',
            false
        )
        ->assertSee('AK2-20260318-ABC123', false)
        ->assertSee('href="' . route('products.index') . '" class="checkout-success-link"', false)
        ->assertSee('data-i18n="continue-shopping">Continue Shopping</span>', false);
});
