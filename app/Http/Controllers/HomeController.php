<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\StorefrontPage;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Schema;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $now = now();

        $categories = Category::query()
            ->select(['id', 'parent_id', 'name', 'slug', 'description', 'image_path', 'translations'])
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->limit(4)
            ->get();

        $products = Product::query()
            ->where('is_active', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', $now)
            ->with([
                'category:id,name,slug,translations',
                'images' => fn ($query) => $query
                    ->select(['id', 'product_id', 'image_path', 'alt_text', 'is_primary', 'sort_order', 'translations'])
                    ->orderByDesc('is_primary')
                    ->orderBy('sort_order'),
            ]);

        $featuredProducts = (clone $products)
            ->where('is_featured', true)
            ->orderByDesc('published_at')
            ->limit(4)
            ->get();

        $newArrivals = (clone $products)
            ->orderByDesc('published_at')
            ->limit(4)
            ->get();

        $banners = collect();

        if (Schema::hasTable((new Banner)->getTable())) {
            $banners = Banner::query()
                ->whereIn('placement', ['homepage_hero', 'homepage_promotion'])
                ->where('is_active', true)
                ->where(fn ($query) => $query->whereNull('starts_at')->orWhere('starts_at', '<=', $now))
                ->where(fn ($query) => $query->whereNull('ends_at')->orWhere('ends_at', '>=', $now))
                ->orderBy('sort_order')
                ->get()
                ->groupBy('placement')
                ->map(fn ($placementBanners) => $placementBanners->first());
        }

        $page = Schema::hasTable((new StorefrontPage)->getTable())
            ? StorefrontPage::query()
                ->where('slug', 'home')
                ->where('is_published', true)
                ->first()
            : null;

        $homeSections = collect($page?->sections ?? [])
            ->filter(fn ($section): bool => is_array($section))
            ->keyBy('key')
            ->all();

        return view('pages.home', [
            'page' => $page,
            'homeSections' => $homeSections,
            'categories' => $categories,
            'featuredProducts' => $featuredProducts,
            'newArrivals' => $newArrivals,
            'heroBanner' => $banners->get('homepage_hero'),
            'promotionBanner' => $banners->get('homepage_promotion'),
        ]);
    }
}
