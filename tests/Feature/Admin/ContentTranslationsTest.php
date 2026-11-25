<?php

use App\Filament\Resources\BannerResource\Pages\CreateBanner;
use App\Filament\Resources\CategoryResource\Pages\CreateCategory;
use App\Filament\Resources\ProductResource\Pages\CreateProduct;
use App\Filament\Resources\StorefrontPageResource\Pages\CreateStorefrontPage;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\StorefrontPage;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

it('shows Arabic product fields alongside the existing English fields', function () {
    User::factory()->create();
    $administrator = User::factory()->create();

    $response = $this->actingAs($administrator)
        ->get(route('legacy-admin.products.create'));

    $response->assertOk()
        ->assertSee('Product Name (English) *')
        ->assertSee('اسم المنتج (عربي)')
        ->assertSee('name="name"', false)
        ->assertSee('name="translations[ar][name]"', false)
        ->assertSee('Product Description (English)')
        ->assertSee('وصف المنتج (عربي)')
        ->assertSee('name="description"', false)
        ->assertSee('name="translations[ar][description]"', false)
        ->assertSee('alt_texts_ar[]', false);
});

it('shows Arabic labels for bilingual content fields in Filament', function () {
    User::factory()->create();
    $administrator = User::factory()->create();

    $this->actingAs($administrator);

    Livewire::test(CreateProduct::class)
        ->fillForm([
            'variants' => [
                [
                    'sku' => 'AK2-LABEL-CHECK-001',
                    'size' => 'Large',
                    'color' => 'Navy',
                    'translations' => [
                        'ar' => [
                            'size' => 'كبير',
                            'color' => 'كحلي',
                        ],
                    ],
                    'is_active' => true,
                ],
            ],
            'images' => [
                [
                    'image_path' => 'products/label-check.jpg',
                    'alt_text' => 'Product image',
                    'translations' => [
                        'ar' => [
                            'alt_text' => 'صورة المنتج',
                        ],
                    ],
                ],
            ],
        ])
        ->assertSee('Name (English)')
        ->assertSee('اسم المنتج (عربي)')
        ->assertSee('Description (English)')
        ->assertSee('وصف المنتج (عربي)')
        ->assertSee('Size (English)')
        ->assertSee('المقاس (عربي)')
        ->assertSee('Color (English)')
        ->assertSee('اللون (عربي)')
        ->assertSee('Alt text (English)')
        ->assertSee('النص البديل للصورة (عربي)');

    Livewire::test(CreateCategory::class)
        ->assertSee('Name (English)')
        ->assertSee('اسم التصنيف (عربي)')
        ->assertSee('Description (English)')
        ->assertSee('وصف التصنيف (عربي)');

    Livewire::test(CreateBanner::class)
        ->assertSee('Title (English)')
        ->assertSee('العنوان (عربي)')
        ->assertSee('Description (English)')
        ->assertSee('الوصف (عربي)')
        ->assertSee('Button text (English)')
        ->assertSee('نص الزر (عربي)');

    Livewire::test(CreateStorefrontPage::class)
        ->fillForm([
            'slug' => 'home',
            'title' => 'Home',
            'sections' => [
                [
                    'key' => 'promotion',
                    'heading' => 'Elevate your everyday style',
                    'translations' => [
                        'ar' => [
                            'heading' => 'ارتقِ بأناقتك اليومية',
                        ],
                    ],
                    'is_active' => true,
                    'links' => [
                        [
                            'group' => 'navigation',
                            'label' => 'Help',
                            'url' => '/contact',
                            'translations' => [
                                'ar' => [
                                    'label' => 'الدعم',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ])
        ->assertSee('Title (English)')
        ->assertSee('العنوان (عربي)')
        ->assertSee('SEO title (English)')
        ->assertSee('عنوان SEO (عربي)')
        ->assertSee('Lower homepage banner - بانر أسفل المنتجات: ارتقِ بأناقتك اليومية')
        ->assertSee('Eyebrow (English)')
        ->assertSee('النص التمهيدي (عربي)')
        ->assertSee('العنوان الرئيسي (عربي)')
        ->assertSee('Button text (English)')
        ->assertSee('نص الزر (عربي)')
        ->assertSee('النص الإرشادي (عربي)')
        ->assertSee('النص التوضيحي (عربي)')
        ->assertSee('النص الثانوي (عربي)')
        ->assertSee('تسمية الرابط (عربي)');
});

it('stores Arabic product details and preserves the existing product fields', function () {
    User::factory()->create();
    $administrator = User::factory()->create();
    $category = Category::create([
        'name' => 'T-Shirts',
        'slug' => 'mens-t-shirts',
    ]);

    $response = $this->actingAs($administrator)->post(route('legacy-admin.products.store'), [
        'category_id' => $category->id,
        'name' => 'Classic T-Shirt',
        'sku' => 'AK2-TEST-TRANSLATION-001',
        'description' => 'English description',
        'translations' => [
            'ar' => [
                'name' => 'قميص كلاسيكي',
                'description' => 'وصف عربي',
            ],
        ],
        'price' => '250.00',
        'has_variants' => '0',
        'stock_quantity' => '8',
    ]);

    $response->assertRedirect(route('legacy-admin.products.index'));

    $product = Product::query()
        ->where('sku', 'AK2-TEST-TRANSLATION-001')
        ->firstOrFail();

    expect($product->name)->toBe('Classic T-Shirt')
        ->and($product->description)->toBe('English description')
        ->and($product->translations)->toBe([
            'ar' => [
                'name' => 'قميص كلاسيكي',
                'description' => 'وصف عربي',
            ],
        ])
        ->and($product->price)->toBe('250.00')
        ->and($product->stock_quantity)->toBe(8);
});

it('saves Arabic product details from the Filament form', function () {
    User::factory()->create();
    $administrator = User::factory()->create();
    $category = Category::create([
        'name' => 'T-Shirts',
        'slug' => 'mens-t-shirts',
    ]);

    $this->actingAs($administrator);

    Livewire::test(CreateProduct::class)
        ->fillForm([
            'category_id' => $category->id,
            'name' => 'Classic T-Shirt',
            'translations' => [
                'ar' => [
                    'name' => 'قميص كلاسيكي',
                    'description' => 'وصف عربي',
                ],
            ],
            'slug' => 'classic-t-shirt',
            'sku' => 'AK2-TEST-FILAMENT-001',
            'description' => 'English description',
            'price' => '250.00',
            'compare_at_price' => null,
            'stock_quantity' => 8,
            'is_active' => false,
            'is_featured' => false,
            'variants' => [],
            'images' => [],
        ])
        ->call('create')
        ->assertNotified();

    $product = Product::query()
        ->where('sku', 'AK2-TEST-FILAMENT-001')
        ->firstOrFail();

    expect($product->translations)->toBe([
        'ar' => [
            'name' => 'قميص كلاسيكي',
            'description' => 'وصف عربي',
        ],
    ])->and($product->stock_quantity)->toBe(8);
});

it('saves Arabic category details from the Filament form', function () {
    User::factory()->create();
    $administrator = User::factory()->create();

    $this->actingAs($administrator);

    Livewire::test(CreateCategory::class)
        ->fillForm([
            'name' => 'Menswear',
            'translations' => [
                'ar' => [
                    'name' => 'ملابس رجالية',
                    'description' => 'وصف التصنيف',
                ],
            ],
            'slug' => 'menswear',
            'parent_id' => null,
            'sort_order' => 1,
            'description' => 'English category description',
            'image_path' => null,
            'is_active' => true,
        ])
        ->call('create')
        ->assertNotified();

    $category = Category::query()->where('slug', 'menswear')->firstOrFail();

    expect($category->name)->toBe('Menswear')
        ->and($category->translations)->toBe([
            'ar' => [
                'name' => 'ملابس رجالية',
                'description' => 'وصف التصنيف',
            ],
        ]);
});

it('saves the lower banner translations independently in Filament', function () {
    User::factory()->create();
    $administrator = User::factory()->create();

    Storage::fake('public');
    $this->actingAs($administrator);

    Livewire::test(CreateBanner::class)
        ->fillForm([
            'title' => 'Seasonal offer',
            'translations' => [
                'ar' => [
                    'title' => 'عرض موسمي',
                    'subtitle' => 'وصف العرض',
                    'button_text' => 'تسوق الآن',
                    'alt_text' => 'صورة العرض',
                ],
            ],
            'placement' => 'homepage_promotion',
            'subtitle' => 'English promotion copy',
            'image_path' => UploadedFile::fake()->image('promotion.png'),
            'mobile_image_path' => null,
            'alt_text' => 'Promotion image',
            'button_text' => 'Shop now',
            'button_url' => 'https://example.test/products',
            'is_active' => true,
            'sort_order' => 1,
            'starts_at' => null,
            'ends_at' => null,
        ])
        ->call('create')
        ->assertNotified();

    $banner = Banner::query()->where('placement', 'homepage_promotion')->firstOrFail();

    expect($banner->translations['ar']['title'])->toBe('عرض موسمي')
        ->and($banner->translations['ar']['subtitle'])->toBe('وصف العرض')
        ->and($banner->translations['ar']['button_text'])->toBe('تسوق الآن')
        ->and($banner->translations['ar']['alt_text'])->toBe('صورة العرض')
        ->and($banner->is_active)->toBeTrue();

    expect(Storage::disk('public')->exists($banner->image_path))->toBeTrue();
});

it('saves Arabic page and section content from the Filament form', function () {
    User::factory()->create();
    $administrator = User::factory()->create();

    $this->actingAs($administrator);

    Livewire::test(CreateStorefrontPage::class)
        ->fillForm([
            'slug' => 'bilingual-page',
            'title' => 'About AK2',
            'translations' => [
                'ar' => [
                    'title' => 'عن AK2',
                    'heading' => 'قصتنا',
                ],
            ],
            'is_published' => true,
            'sections' => [
                [
                    'key' => 'content',
                    'heading' => 'Our story',
                    'translations' => [
                        'ar' => [
                            'heading' => 'قصتنا',
                        ],
                    ],
                    'is_active' => true,
                    'links' => [],
                ],
            ],
        ])
        ->call('create')
        ->assertNotified();

    $page = StorefrontPage::query()->where('slug', 'bilingual-page')->firstOrFail();

    expect($page->translations['ar']['title'])->toBe('عن AK2')
        ->and($page->sections[0]['translations']['ar']['heading'])->toBe('قصتنا');
});

it('stores translated variant and image text without changing their operational data', function () {
    User::factory()->create();
    $administrator = User::factory()->create();
    $category = Category::create([
        'name' => 'T-Shirts',
        'slug' => 'mens-t-shirts',
    ]);

    Storage::fake('public');

    $this->actingAs($administrator)
        ->post(route('legacy-admin.products.store'), [
            'category_id' => $category->id,
            'name' => 'Classic T-Shirt',
            'sku' => 'AK2-TEST-TRANSLATION-003',
            'price' => '250.00',
            'has_variants' => '1',
            'variants' => [
                [
                    'sku' => 'AK2-TEST-TRANSLATION-003-M',
                    'size' => 'M',
                    'color' => 'Black',
                    'color_code' => '#000000',
                    'price' => '275.00',
                    'stock_quantity' => '3',
                    'is_active' => '1',
                    'translations' => [
                        'ar' => [
                            'size' => 'متوسط',
                            'color' => 'أسود',
                        ],
                    ],
                ],
            ],
            'images' => [
                UploadedFile::fake()->image('front.png'),
            ],
            'alt_texts' => ['Front view'],
            'alt_texts_ar' => ['الواجهة الأمامية'],
        ]);

    $product = Product::query()
        ->where('sku', 'AK2-TEST-TRANSLATION-003')
        ->firstOrFail();
    $variant = $product->variants()->firstOrFail();
    $image = $product->images()->firstOrFail();

    expect($product->stock_quantity)->toBe(0)
        ->and($variant->sku)->toBe('AK2-TEST-TRANSLATION-003-M')
        ->and($variant->size)->toBe('M')
        ->and($variant->color)->toBe('Black')
        ->and($variant->price)->toBe('275.00')
        ->and($variant->stock_quantity)->toBe(3)
        ->and($variant->translations)->toBe([
            'ar' => [
                'size' => 'متوسط',
                'color' => 'أسود',
            ],
        ])
        ->and($image->alt_text)->toBe('Front view')
        ->and($image->translations)->toBe([
            'ar' => [
                'alt_text' => 'الواجهة الأمامية',
            ],
        ]);

    expect(Storage::disk('public')->exists($image->image_path))->toBeTrue();
});

it('rejects Arabic product names that exceed the existing name limit', function () {
    User::factory()->create();
    $administrator = User::factory()->create();
    $category = Category::create([
        'name' => 'T-Shirts',
        'slug' => 'mens-t-shirts',
    ]);

    $response = $this->actingAs($administrator)->post(route('legacy-admin.products.store'), [
        'category_id' => $category->id,
        'name' => 'Classic T-Shirt',
        'sku' => 'AK2-TEST-TRANSLATION-002',
        'translations' => [
            'ar' => [
                'name' => str_repeat('ا', 201),
            ],
        ],
        'price' => '250.00',
        'has_variants' => '0',
        'stock_quantity' => '8',
    ]);

    $response->assertSessionHasErrors(['translations.ar.name']);

    expect(Product::query()->where('sku', 'AK2-TEST-TRANSLATION-002')->exists())->toBeFalse();
});
