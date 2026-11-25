<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'Classic Essential T-Shirt',
                'slug' => 'classic-essential-t-shirt',
                'sku' => 'AK2-MEN-TSHIRT-001',
                'category' => 'mens-t-shirts',
                'description' => 'A comfortable everyday essential T-shirt.',
                'price' => 450,
                'compare_at_price' => 600,
                'is_featured' => true,
                'image' => 'men-tshirt.png',
                'variants' => [
                    ['size' => 'S', 'color' => 'Black', 'color_code' => '#000000', 'stock' => 10],
                    ['size' => 'M', 'color' => 'Black', 'color_code' => '#000000', 'stock' => 15],
                    ['size' => 'L', 'color' => 'Black', 'color_code' => '#000000', 'stock' => 12],
                    ['size' => 'S', 'color' => 'White', 'color_code' => '#FFFFFF', 'stock' => 8],
                    ['size' => 'M', 'color' => 'White', 'color_code' => '#FFFFFF', 'stock' => 10],
                    ['size' => 'L', 'color' => 'White', 'color_code' => '#FFFFFF', 'stock' => 7],
                ],
            ],
            [
                'name' => 'Premium Oxford Shirt',
                'slug' => 'premium-oxford-shirt',
                'sku' => 'AK2-MEN-SHIRT-001',
                'category' => 'mens-shirts',
                'description' => 'A versatile Oxford shirt for everyday and smart casual looks.',
                'price' => 750,
                'compare_at_price' => null,
                'is_featured' => true,
                'image' => 'men-shirt.png',
                'variants' => [
                    ['size' => 'S', 'color' => null, 'color_code' => null, 'stock' => 5],
                    ['size' => 'M', 'color' => null, 'color_code' => null, 'stock' => 8],
                    ['size' => 'L', 'color' => null, 'color_code' => null, 'stock' => 6],
                ],
            ],
            [
                'name' => 'Elegant Midi Dress',
                'slug' => 'elegant-midi-dress',
                'sku' => 'AK2-WOM-DRESS-001',
                'category' => 'womens-dresses',
                'description' => 'An elegant midi dress with a timeless design.',
                'price' => 1200,
                'compare_at_price' => 1500,
                'is_featured' => false,
                'image' => 'women-dress.png',
                'variants' => [],
            ],
        ];

        foreach ($products as $data) {
            $category = Category::where('slug', $data['category'])
                ->firstOrFail();

            $product = Product::updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'category_id' => $category->id,
                    'name' => $data['name'],
                    'sku' => $data['sku'],
                    'description' => $data['description'],
                    'price' => $data['price'],
                    'compare_at_price' => $data['compare_at_price'],
                    'is_active' => true,
                    'is_featured' => $data['is_featured'],
                    'published_at' => now(),
                ]
            );

            foreach ($data['variants'] as $index => $variant) {
                ProductVariant::updateOrCreate(
                    ['sku' => $data['sku'] . '-' . ($index + 1)],
                    [
                        'product_id' => $product->id,
                        'size' => $variant['size'],
                        'color' => $variant['color'],
                        'color_code' => $variant['color_code'],
                        'price' => null,
                        'stock_quantity' => $variant['stock'],
                        'is_active' => true,
                    ]
                );
            }

            $imagePath = 'products/' . $data['image'];

            if (Storage::disk('public')->exists($imagePath)) {
                ProductImage::updateOrCreate(
                    [
                        'product_id' => $product->id,
                        'image_path' => $imagePath,
                    ],
                    [
                        'alt_text' => $data['name'],
                        'is_primary' => true,
                        'sort_order' => 1,
                    ]
                );
            }
        }
    }
}
