<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Contracts\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
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

        return view('pages.categories', compact('categories'));
    }
}
