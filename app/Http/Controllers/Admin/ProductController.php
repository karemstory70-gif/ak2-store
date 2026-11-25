<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;
use Throwable;

class ProductController extends Controller
{
    /**
     * Display all products.
     */
    public function index(Request $request): View
    {
        $productsQuery = Product::query()
            ->with([
                'category',
                'images' => fn ($query) => $query
                    ->orderByDesc('is_primary')
                    ->orderBy('sort_order'),
                'variants',
            ])
            ->withCount('variants');

        if ($request->filled('search')) {
            $search = $request->string('search')->trim()->toString();

            $productsQuery->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $productsQuery->where('category_id', $request->integer('category'));
        }

        if ($request->status === 'active') {
            $productsQuery->where('is_active', true);
        } elseif ($request->status === 'inactive') {
            $productsQuery->where('is_active', false);
        }

        $products = $productsQuery
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $categories = Category::query()
            ->orderBy('name')
            ->get();

        $stats = [
            'total' => Product::count(),
            'active' => Product::where('is_active', true)->count(),
            'inactive' => Product::where('is_active', false)->count(),

            'low_stock' => Product::where(function ($query) {
                $query->where(function ($q) {
                    $q->whereDoesntHave('variants')
                        ->where('stock_quantity', '<=', 5);
                })->orWhereHas('variants', function ($q) {
                    $q->where('is_active', true)
                        ->where('stock_quantity', '<=', 5);
                });
            })->count(),
        ];

        return view('admin.products.index', compact(
            'products',
            'categories',
            'stats'
        ));
    }

    /**
     * Show the create product form.
     */
    public function create(): View
    {
        $categories = Category::query()
            ->orderBy('name')
            ->get();

        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a new product.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => [
                'required',
                'integer',
                'exists:categories,id',
            ],

            'name' => [
                'required',
                'string',
                'max:200',
            ],

            'sku' => [
                'required',
                'string',
                'max:100',
                'unique:products,sku',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'translations' => [
                'nullable',
                'array',
            ],

            'translations.ar' => [
                'nullable',
                'array',
            ],

            'translations.ar.name' => [
                'nullable',
                'string',
                'max:200',
            ],

            'translations.ar.description' => [
                'nullable',
                'string',
            ],

            'price' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            'compare_at_price' => [
                'nullable',
                'numeric',
                'gt:price',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],

            'is_featured' => [
                'nullable',
                'boolean',
            ],

            'has_variants' => [
                'required',
                'boolean',
            ],

            'stock_quantity' => [
                'required_unless:has_variants,1',
                'nullable',
                'integer',
                'min:0',
            ],

            'variants' => [
                'required_if:has_variants,1',
                'array',
                'min:1',
            ],

            'variants.*.sku' => [
                'required',
                'string',
                'max:100',
                'distinct',
                'unique:product_variants,sku',
            ],

            'variants.*.size' => [
                'nullable',
                'string',
                'max:50',
            ],

            'variants.*.color' => [
                'nullable',
                'string',
                'max:100',
            ],

            'variants.*.color_code' => [
                'nullable',
                'string',
                'max:20',
            ],

            'variants.*.price' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'variants.*.stock_quantity' => [
                'required',
                'integer',
                'min:0',
            ],

            'variants.*.is_active' => [
                'nullable',
                'boolean',
            ],

            'variants.*.translations' => [
                'nullable',
                'array',
            ],

            'variants.*.translations.ar' => [
                'nullable',
                'array',
            ],

            'variants.*.translations.ar.size' => [
                'nullable',
                'string',
                'max:50',
            ],

            'variants.*.translations.ar.color' => [
                'nullable',
                'string',
                'max:100',
            ],

            'images' => [
                'nullable',
                'array',
                'max:10',
            ],

            'images.*' => [
                'required',
                File::image()
                    ->types(['jpg', 'jpeg', 'png', 'webp'])
                    ->max(5 * 1024),
            ],

            'alt_texts' => [
                'nullable',
                'array',
            ],

            'alt_texts.*' => [
                'nullable',
                'string',
                'max:255',
            ],

            'alt_texts_ar' => [
                'nullable',
                'array',
            ],

            'alt_texts_ar.*' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);

        $uploadedPaths = [];

        try {
            DB::transaction(function () use (
                $validated,
                $request,
                &$uploadedPaths
            ) {
                // Generate a unique product slug.
                $baseSlug = Str::slug($validated['name']);

                if ($baseSlug === '') {
                    $baseSlug = 'product';
                }

                $slug = $baseSlug;
                $counter = 1;

                while (Product::where('slug', $slug)->exists()) {
                    $slug = $baseSlug . '-' . $counter;
                    $counter++;
                }

                $hasVariants = (bool) $validated['has_variants'];
                $isActive = $request->boolean('is_active');

                // Create the main product.
                $product = Product::create([
                    'category_id' => $validated['category_id'],
                    'name' => $validated['name'],
                    'slug' => $slug,
                    'sku' => $validated['sku'],
                    'description' => $validated['description'] ?? null,
                    'translations' => $validated['translations'] ?? null,
                    'price' => $validated['price'],
                    'compare_at_price' => $validated['compare_at_price'] ?? null,

                    // Variants own the stock when they exist.
                    'stock_quantity' => $hasVariants
                        ? 0
                        : $validated['stock_quantity'],

                    'is_active' => $isActive,
                    'is_featured' => $request->boolean('is_featured'),
                    'published_at' => $isActive ? now() : null,
                ]);

                // Create product variants.
                if ($hasVariants) {
                    foreach ($validated['variants'] as $variantData) {
                        $product->variants()->create([
                            'sku' => $variantData['sku'],
                            'size' => $variantData['size'] ?? null,
                            'color' => $variantData['color'] ?? null,
                            'color_code' => $variantData['color_code'] ?? null,
                            'price' => $variantData['price'] ?? null,
                            'stock_quantity' => $variantData['stock_quantity'],
                            'is_active' => (bool) ($variantData['is_active'] ?? true),
                            'translations' => $variantData['translations'] ?? null,
                        ]);
                    }
                }

                // Upload and save product images.
                if ($request->hasFile('images')) {
                    foreach ($request->file('images') as $index => $image) {
                        $path = $image->store('products', 'public');

                        if (! $path) {
                            throw new \RuntimeException(
                                'Unable to store a product image.'
                            );
                        }

                        $uploadedPaths[] = $path;

                        $product->images()->create([
                            'image_path' => $path,
                            'alt_text' => $validated['alt_texts'][$index] ?? $product->name,
                            'translations' => filled($validated['alt_texts_ar'][$index] ?? null)
                                ? ['ar' => ['alt_text' => $validated['alt_texts_ar'][$index]]]
                                : null,
                            'is_primary' => $index === 0,
                            'sort_order' => $index,
                        ]);
                    }
                }
            });
        } catch (Throwable $exception) {
            // Remove uploaded files if the database operation fails.
            if (! empty($uploadedPaths)) {
                Storage::disk('public')->delete($uploadedPaths);
            }

            report($exception);

            return back()
                ->withInput()
                ->withErrors([
                    'general' => 'The product could not be created. Please try again.',
                ]);
        }

        return redirect()
            ->route('legacy-admin.products.index')
            ->with('success', 'Product created successfully.');
    }
}
