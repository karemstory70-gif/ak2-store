
@extends('layouts.store')

@section('title', 'Manage Products | AK2')

@section('content')
<section class="min-h-screen bg-[#080B12] py-10 text-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        {{-- Page Header --}}
        <div class="mb-10 flex flex-wrap items-center justify-between gap-4">
            <div>
                <p class="mb-2 text-sm tracking-[0.3em] text-[#D4AF37]">
                    AK2 TECHNOLOGIES
                </p>

                <h1 class="text-3xl font-bold">Products & Inventory</h1>

                <p class="mt-2 text-gray-400">
                    Manage your store products, prices and stock levels.
                </p>
            </div>

            <a href="{{ route('legacy-admin.dashboard') }}"
               class="rounded-lg border border-white/10 px-5 py-3 font-semibold text-gray-300 transition hover:bg-white/5">
                Back to Dashboard
            </a>
        </div>

        {{-- Statistics --}}
        <div class="mb-10 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">

            @php
                $cards = [
                    [
                        'label' => 'Total Products',
                        'value' => number_format($stats['total']),
                        'description' => 'All registered products',
                    ],
                    [
                        'label' => 'Active Products',
                        'value' => number_format($stats['active']),
                        'description' => 'Visible in the storefront',
                    ],
                    [
                        'label' => 'Inactive Products',
                        'value' => number_format($stats['inactive']),
                        'description' => 'Currently hidden',
                    ],
                    [
                        'label' => 'Low Stock',
                        'value' => number_format($stats['low_stock']),
                        'description' => 'Products needing attention',
                    ],
                ];
            @endphp

            @foreach($cards as $card)
                <div class="rounded-xl border border-white/10 bg-[#111722] p-5">
                    <p class="text-sm text-gray-400">
                        {{ $card['label'] }}
                    </p>

                    <p class="mt-4 text-2xl font-bold text-[#D4AF37]">
                        {{ $card['value'] }}
                    </p>

                    <p class="mt-2 text-xs text-gray-500">
                        {{ $card['description'] }}
                    </p>
                </div>
            @endforeach

        </div>

        {{-- Products Table --}}
        <div class="overflow-hidden rounded-xl border border-white/10 bg-[#111722]">

            {{-- Table Header --}}
            <div class="border-b border-white/10 p-6">
                <h2 class="text-xl font-bold">All Products</h2>

                <p class="mt-1 text-sm text-gray-400">
                    Browse and monitor your store inventory.
                </p>
            </div>

            {{-- Search and Filters --}}
            <form method="GET"
                  action="{{ route('legacy-admin.products.index') }}"
                  class="grid grid-cols-1 gap-4 border-b border-white/10 p-6 md:grid-cols-4">

                <div class="md:col-span-2">
                    <label for="search" class="mb-2 block text-sm text-gray-400">
                        Search Products
                    </label>

                    <input
                        type="text"
                        id="search"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search by product name or SKU..."
                        class="w-full rounded-lg border border-white/10 bg-[#080B12] px-4 py-3 text-sm text-white outline-none transition placeholder:text-gray-600 focus:border-[#D4AF37]"
                    >
                </div>

                <div>
                    <label for="category" class="mb-2 block text-sm text-gray-400">
                        Category
                    </label>

                    <select
                        id="category"
                        name="category"
                        class="w-full rounded-lg border border-white/10 bg-[#080B12] px-4 py-3 text-sm text-white outline-none focus:border-[#D4AF37]"
                    >
                        <option value="">All Categories</option>

                        @foreach($categories as $category)
                            <option
                                value="{{ $category->id }}"
                                @selected((string) request('category') === (string) $category->id)
                            >
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="status" class="mb-2 block text-sm text-gray-400">
                        Product Status
                    </label>

                    <select
                        id="status"
                        name="status"
                        class="w-full rounded-lg border border-white/10 bg-[#080B12] px-4 py-3 text-sm text-white outline-none focus:border-[#D4AF37]"
                    >
                        <option value="">All Statuses</option>
                        <option value="active" @selected(request('status') === 'active')>
                            Active
                        </option>
                        <option value="inactive" @selected(request('status') === 'inactive')>
                            Inactive
                        </option>
                    </select>
                </div>

                <div class="flex flex-wrap gap-3 md:col-span-4">
                    <button
                        type="submit"
                        class="rounded-lg bg-[#D4AF37] px-6 py-3 text-sm font-bold text-black transition hover:bg-[#e8c65b]"
                    >
                        Apply Filters
                    </button>

                    <a
                        href="{{ route('legacy-admin.products.index') }}"
                        class="rounded-lg border border-white/10 px-6 py-3 text-sm font-semibold text-gray-300 transition hover:bg-white/5"
                    >
                        Reset
                    </a>
                </div>
            </form>

            {{-- Products Table --}}
            <div class="overflow-x-auto">
                <table class="w-full min-w-[900px] text-left text-sm">

                    <thead class="bg-white/5 text-gray-400">
                        <tr>
                            <th class="px-6 py-4">Product</th>
                            <th class="px-6 py-4">Category</th>
                            <th class="px-6 py-4">SKU</th>
                            <th class="px-6 py-4">Price</th>
                            <th class="px-6 py-4">Inventory</th>
                            <th class="px-6 py-4">Variants</th>
                            <th class="px-6 py-4">Status</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-white/10">

                        @forelse($products as $product)

                            @php
                                $activeVariants = $product->variants->where('is_active', true);

                                $stock = $product->variants_count > 0
                                    ? $activeVariants->sum('stock_quantity')
                                    : $product->stock_quantity;

                                $hasLowStock = $product->variants_count > 0
                                    ? $activeVariants->contains(fn ($variant) => $variant->stock_quantity <= 5)
                                    : $product->stock_quantity <= 5;

                                $mainImage = $product->images->first();
                            @endphp

                            <tr class="transition hover:bg-white/[0.03]">

                                {{-- Product --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">

                                        @if($mainImage)
                                            <img
                                                src="{{ asset('storage/' . $mainImage->image_path) }}"
                                                alt="{{ $mainImage->alt_text ?: $product->name }}"
                                                class="h-14 w-14 rounded-lg border border-white/10 object-cover"
                                            >
                                        @else
                                            <div class="flex h-14 w-14 items-center justify-center rounded-lg border border-white/10 bg-white/5 text-xs text-gray-500">
                                                No Image
                                            </div>
                                        @endif

                                        <div>
                                            <p class="font-semibold text-white">
                                                {{ $product->name }}
                                            </p>

                                            <p class="mt-1 text-xs text-gray-500">
                                                {{ $product->is_featured ? 'Featured Product' : 'Regular Product' }}
                                            </p>
                                        </div>

                                    </div>
                                </td>

                                {{-- Category --}}
                                <td class="px-6 py-4 text-gray-300">
                                    {{ $product->category?->name ?? 'Uncategorized' }}
                                </td>

                                {{-- SKU --}}
                                <td class="px-6 py-4 font-mono text-xs text-gray-400">
                                    {{ $product->sku }}
                                </td>

                                {{-- Price --}}
                                <td class="px-6 py-4">
                                    <p class="font-semibold text-white">
                                        {{ number_format($product->price, 2) }} EGP
                                    </p>

                                    @if($product->compare_at_price)
                                        <p class="mt-1 text-xs text-gray-500 line-through">
                                            {{ number_format($product->compare_at_price, 2) }} EGP
                                        </p>
                                    @endif
                                </td>

                                {{-- Inventory --}}
                                <td class="px-6 py-4">
                                    <p class="font-semibold {{ $hasLowStock ? 'text-red-400' : 'text-green-400' }}">
                                        {{ number_format($stock) }} units
                                    </p>

                                    @if($hasLowStock)
                                        <p class="mt-1 text-xs text-red-400">
                                            Low Stock
                                        </p>
                                    @else
                                        <p class="mt-1 text-xs text-gray-500">
                                            Available
                                        </p>
                                    @endif
                                </td>

                                {{-- Variants --}}
                                <td class="px-6 py-4 text-gray-300">
                                    {{ $product->variants_count }}
                                </td>

                                {{-- Status --}}
                                <td class="px-6 py-4">
                                    @if($product->is_active)
                                        <span class="rounded-full border border-green-500/20 bg-green-500/10 px-3 py-1 text-xs font-semibold text-green-400">
                                            Active
                                        </span>
                                    @else
                                        <span class="rounded-full border border-gray-500/20 bg-gray-500/10 px-3 py-1 text-xs font-semibold text-gray-400">
                                            Inactive
                                        </span>
                                    @endif
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center">
                                    <p class="text-lg font-semibold text-gray-300">
                                        No products found
                                    </p>

                                    <p class="mt-2 text-sm text-gray-500">
                                        Try changing your search or filters.
                                    </p>
                                </td>
                            </tr>

                        @endforelse

                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($products->hasPages())
                <div class="border-t border-white/10 p-6">
                    {{ $products->links() }}
                </div>
            @endif

            <div class="border-t border-white/10 px-6 py-4 text-xs text-gray-500">
                Showing {{ $products->firstItem() ?? 0 }}
                to {{ $products->lastItem() ?? 0 }}
                of {{ $products->total() }} products
            </div>

        </div>

    </div>
</section>
@endsection
