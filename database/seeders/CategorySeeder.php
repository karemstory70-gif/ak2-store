<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Men',
                'slug' => 'men',
                'description' => "Discover the latest men's fashion collection.",
                'sort_order' => 1,
                'children' => [
                    ['name' => 'T-Shirts', 'slug' => 'mens-t-shirts', 'sort_order' => 1],
                    ['name' => 'Shirts', 'slug' => 'mens-shirts', 'sort_order' => 2],
                    ['name' => 'Pants', 'slug' => 'mens-pants', 'sort_order' => 3],
                ],
            ],
            [
                'name' => 'Women',
                'slug' => 'women',
                'description' => "Explore our women's fashion collection.",
                'sort_order' => 2,
                'children' => [
                    ['name' => 'Tops', 'slug' => 'womens-tops', 'sort_order' => 1],
                    ['name' => 'Dresses', 'slug' => 'womens-dresses', 'sort_order' => 2],
                    ['name' => 'Pants', 'slug' => 'womens-pants', 'sort_order' => 3],
                ],
            ],
            [
                'name' => 'Kids',
                'slug' => 'kids',
                'description' => 'Comfortable and stylish fashion for kids.',
                'sort_order' => 3,
                'children' => [
                    ['name' => 'Boys', 'slug' => 'boys', 'sort_order' => 1],
                    ['name' => 'Girls', 'slug' => 'girls', 'sort_order' => 2],
                ],
            ],
            [
                'name' => 'Shoes',
                'slug' => 'shoes',
                'description' => 'Find footwear for every style.',
                'sort_order' => 4,
                'children' => [
                    ['name' => 'Men Shoes', 'slug' => 'mens-shoes', 'sort_order' => 1],
                    ['name' => 'Women Shoes', 'slug' => 'womens-shoes', 'sort_order' => 2],
                    ['name' => 'Kids Shoes', 'slug' => 'kids-shoes', 'sort_order' => 3],
                ],
            ],
        ];

        foreach ($categories as $categoryData) {
            $children = $categoryData['children'];

            unset($categoryData['children']);

            $parent = Category::updateOrCreate(
                ['slug' => $categoryData['slug']],
                [
                    ...$categoryData,
                    'parent_id' => null,
                    'is_active' => true,
                ]
            );

            foreach ($children as $childData) {
                Category::updateOrCreate(
                    ['slug' => $childData['slug']],
                    [
                        ...$childData,
                        'parent_id' => $parent->id,
                        'is_active' => true,
                        'description' => null,
                        'image_path' => null,
                    ]
                );
            }
        }
    }
}
