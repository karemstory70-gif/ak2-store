<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $category = null;

        $productsQuery = Product::query()
            ->where('is_active', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->with([
                'category',
                'images' => function ($query) {
                    $query->orderByDesc('is_primary')
                        ->orderBy('sort_order');
                },
            ]);

        if ($request->filled('category')) {
            $category = Category::query()
                ->where('slug', $request->query('category'))
                ->where('is_active', true)
                ->firstOrFail();

            $categoryIds = collect([$category->id])
                ->merge($category->children()
                    ->where('is_active', true)
                    ->pluck('id'));

            $productsQuery->whereIn('category_id', $categoryIds);
        }

        $products = $productsQuery
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $categories = Category::query()
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->with([
                'children' => function ($query) {
                    $query->where('is_active', true)
                        ->orderBy('sort_order');
                },
            ])
            ->orderBy('sort_order')
            ->get();

        return view('pages.products', compact(
            'products',
            'categories',
            'category'
        ));
    }

    public function show(string $slug): View
    {
        $product = Product::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->with([
                'category',
                'images' => function ($query) {
                    $query->orderByDesc('is_primary')
                        ->orderBy('sort_order');
                },
                'variants' => function ($query) {
                    $query->where('is_active', true);
                },
            ])
            ->firstOrFail();

        return view('pages.product-details', compact('product'));
    }
}
