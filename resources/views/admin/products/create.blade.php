
@extends('layouts.store')

@section('title', 'Create Product | AK2')

@section('content')
<section class="min-h-screen bg-[#080B12] py-10 text-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-10 flex flex-wrap items-center justify-between gap-4">
            <div>
                <p class="mb-2 text-sm tracking-[0.3em] text-[#D4AF37]">
                    AK2 TECHNOLOGIES
                </p>
                <h1 class="text-3xl font-bold">Create Product</h1>
                <p class="mt-2 text-gray-400">
                    Add a new product to your store catalog.
                </p>
            </div>

            <a href="{{ route('legacy-admin.products.index') }}"
               class="rounded-lg border border-white/10 px-5 py-3 text-sm font-semibold text-gray-300 transition hover:bg-white/5">
                Back to Products
            </a>
        </div>

        {{-- General Errors --}}
        @if($errors->has('general'))
            <div class="mb-6 rounded-lg border border-red-500/30 bg-red-500/10 p-4 text-sm text-red-300">
                {{ $errors->first('general') }}
            </div>
        @endif

        @if($errors->any() && ! $errors->has('general'))
            <div class="mb-6 rounded-lg border border-red-500/30 bg-red-500/10 p-4">
                <p class="mb-2 font-semibold text-red-300">
                    Please review the following errors:
                </p>
                <ul class="list-inside list-disc space-y-1 text-sm text-red-300">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form
            method="POST"
            action="{{ route('legacy-admin.products.store') }}"
            enctype="multipart/form-data"
            class="space-y-8"
        >
            @csrf

            {{-- Basic Information --}}
            <div class="rounded-xl border border-white/10 bg-[#111722] p-6 sm:p-8">
                <div class="mb-6 border-b border-white/10 pb-5">
                    <h2 class="text-xl font-bold">Basic Information</h2>
                    <p class="mt-1 text-sm text-gray-400">
                        Product name, category, SKU and description.
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                    <div>
                        <label for="name" class="mb-2 block text-sm text-gray-300">
                            Product Name (English) *
                        </label>
                        <input
                            id="name"
                            name="name"
                            type="text"
                            value="{{ old('name') }}"
                            required
                            maxlength="200"
                            placeholder="e.g. Premium Cotton T-Shirt"
                            class="w-full rounded-lg border border-white/10 bg-[#080B12] px-4 py-3 text-white outline-none placeholder:text-gray-600 focus:border-[#D4AF37]"
                        >
                        @error('name')
                            <p class="mt-2 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="name_ar" class="mb-2 block text-sm text-gray-300">
                            اسم المنتج (عربي)
                        </label>
                        <input
                            id="name_ar"
                            name="translations[ar][name]"
                            type="text"
                            value="{{ old('translations.ar.name') }}"
                            maxlength="200"
                            dir="rtl"
                            class="w-full rounded-lg border border-white/10 bg-[#080B12] px-4 py-3 text-white outline-none placeholder:text-gray-600 focus:border-[#D4AF37]"
                        >
                        @error('translations.ar.name')
                            <p class="mt-2 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="sku" class="mb-2 block text-sm text-gray-300">
                            Product SKU *
                        </label>
                        <input
                            id="sku"
                            name="sku"
                            type="text"
                            value="{{ old('sku') }}"
                            required
                            maxlength="100"
                            placeholder="e.g. AK2-PRODUCT-001"
                            class="w-full rounded-lg border border-white/10 bg-[#080B12] px-4 py-3 font-mono text-sm text-white outline-none placeholder:text-gray-600 focus:border-[#D4AF37]"
                        >
                        @error('sku')
                            <p class="mt-2 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="category_id" class="mb-2 block text-sm text-gray-300">
                            Category *
                        </label>
                        <select
                            id="category_id"
                            name="category_id"
                            required
                            class="w-full rounded-lg border border-white/10 bg-[#080B12] px-4 py-3 text-white outline-none focus:border-[#D4AF37]"
                        >
                            <option value="">Select a category</option>
                            @foreach($categories as $category)
                                <option
                                    value="{{ $category->id }}"
                                    @selected((string) old('category_id') === (string) $category->id)
                                >
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-2 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="mb-2 block text-sm text-gray-300">
                            Product Description (English)
                        </label>
                        <textarea
                            id="description"
                            name="description"
                            rows="5"
                            placeholder="Describe your product..."
                            class="w-full rounded-lg border border-white/10 bg-[#080B12] px-4 py-3 text-white outline-none placeholder:text-gray-600 focus:border-[#D4AF37]"
                        >{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-2 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description_ar" class="mb-2 block text-sm text-gray-300">
                            وصف المنتج (عربي)
                        </label>
                        <textarea
                            id="description_ar"
                            name="translations[ar][description]"
                            rows="5"
                            dir="rtl"
                            class="w-full rounded-lg border border-white/10 bg-[#080B12] px-4 py-3 text-white outline-none placeholder:text-gray-600 focus:border-[#D4AF37]"
                        >{{ old('translations.ar.description') }}</textarea>
                        @error('translations.ar.description')
                            <p class="mt-2 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- Pricing --}}
            <div class="rounded-xl border border-white/10 bg-[#111722] p-6 sm:p-8">
                <div class="mb-6 border-b border-white/10 pb-5">
                    <h2 class="text-xl font-bold">Pricing</h2>
                    <p class="mt-1 text-sm text-gray-400">
                        Set the regular price and optional comparison price.
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                    <div>
                        <label for="price" class="mb-2 block text-sm text-gray-300">
                            Regular Price (EGP) *
                        </label>
                        <input
                            id="price"
                            name="price"
                            type="number"
                            step="0.01"
                            min="0.01"
                            value="{{ old('price') }}"
                            required
                            placeholder="750.00"
                            class="w-full rounded-lg border border-white/10 bg-[#080B12] px-4 py-3 text-white outline-none placeholder:text-gray-600 focus:border-[#D4AF37]"
                        >
                        @error('price')
                            <p class="mt-2 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="compare_at_price" class="mb-2 block text-sm text-gray-300">
                            Compare-at Price (EGP)
                        </label>
                        <input
                            id="compare_at_price"
                            name="compare_at_price"
                            type="number"
                            step="0.01"
                            min="0.01"
                            value="{{ old('compare_at_price') }}"
                            placeholder="900.00"
                            class="w-full rounded-lg border border-white/10 bg-[#080B12] px-4 py-3 text-white outline-none placeholder:text-gray-600 focus:border-[#D4AF37]"
                        >
                        <p class="mt-2 text-xs text-gray-500">
                            Optional. Must be higher than the regular price.
                        </p>
                        @error('compare_at_price')
                            <p class="mt-2 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- Inventory --}}
            <div class="rounded-xl border border-white/10 bg-[#111722] p-6 sm:p-8">
                <div class="mb-6 border-b border-white/10 pb-5">
                    <h2 class="text-xl font-bold">Inventory & Variants</h2>
                    <p class="mt-1 text-sm text-gray-400">
                        Choose how this product manages its stock.
                    </p>
                </div>

                <input type="hidden" name="has_variants" id="has_variants" value="{{ old('has_variants', '0') }}">

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                    <label class="flex cursor-pointer items-start gap-3 rounded-lg border border-white/10 bg-[#080B12] p-4">
                        <input
                            type="radio"
                            name="inventory_type"
                            value="simple"
                            class="mt-1 accent-[#D4AF37]"
                            checked
                        >
                        <span>
                            <span class="block font-semibold">Simple Product</span>
                            <span class="mt-1 block text-xs text-gray-400">
                                One stock quantity for the entire product.
                            </span>
                        </span>
                    </label>

                    <label class="flex cursor-pointer items-start gap-3 rounded-lg border border-white/10 bg-[#080B12] p-4">
                        <input
                            type="radio"
                            name="inventory_type"
                            value="variants"
                            class="mt-1 accent-[#D4AF37]"
                        >
                        <span>
                            <span class="block font-semibold">Product with Variants</span>
                            <span class="mt-1 block text-xs text-gray-400">
                                Separate sizes, colors, SKUs and stock.
                            </span>
                        </span>
                    </label>

                </div>

                <div id="simple-stock" class="mt-6">
                    <label for="stock_quantity" class="mb-2 block text-sm text-gray-300">
                        Stock Quantity *
                    </label>
                    <input
                        id="stock_quantity"
                        name="stock_quantity"
                        type="number"
                        min="0"
                        value="{{ old('stock_quantity', 0) }}"
                        class="w-full rounded-lg border border-white/10 bg-[#080B12] px-4 py-3 text-white outline-none focus:border-[#D4AF37] md:max-w-sm"
                    >
                    @error('stock_quantity')
                        <p class="mt-2 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div id="variants-section" class="mt-8 hidden">
                    <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <h3 class="font-bold">Product Variants</h3>
                            <p class="mt-1 text-xs text-gray-400">
                                Add each available size and color combination.
                            </p>
                        </div>

                        <button
                            type="button"
                            id="add-variant"
                            class="rounded-lg border border-[#D4AF37]/40 px-4 py-2 text-sm font-semibold text-[#D4AF37] transition hover:bg-[#D4AF37]/10"
                        >
                            + Add Variant
                        </button>
                    </div>

                    <div id="variants-container" class="space-y-4"></div>

                    @error('variants')
                        <p class="mt-2 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Images --}}
            <div class="rounded-xl border border-white/10 bg-[#111722] p-6 sm:p-8">
                <div class="mb-6 border-b border-white/10 pb-5">
                    <h2 class="text-xl font-bold">Product Images</h2>
                    <p class="mt-1 text-sm text-gray-400">
                        Upload up to 10 images. The first image will be the primary image.
                    </p>
                </div>

                <input
                    type="file"
                    id="images"
                    name="images[]"
                    accept=".jpg,.jpeg,.png,.webp"
                    multiple
                    class="block w-full rounded-lg border border-white/10 bg-[#080B12] p-3 text-sm text-gray-300 file:mr-4 file:rounded-md file:border-0 file:bg-[#D4AF37] file:px-4 file:py-2 file:font-bold file:text-black"
                >

                <p class="mt-2 text-xs text-gray-500">
                    JPG, JPEG, PNG or WEBP. Maximum 5 MB per image.
                </p>

                <div id="image-preview" class="mt-5 grid grid-cols-2 gap-4 sm:grid-cols-4 lg:grid-cols-5"></div>

                @error('images')
                    <p class="mt-2 text-xs text-red-400">{{ $message }}</p>
                @enderror

                @error('images.*')
                    <p class="mt-2 text-xs text-red-400">{{ $message }}</p>
                @enderror
                @error('alt_texts.*')
                    <p class="mt-2 text-xs text-red-400">{{ $message }}</p>
                @enderror
                @error('alt_texts_ar.*')
                    <p class="mt-2 text-xs text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Publishing --}}
            <div class="rounded-xl border border-white/10 bg-[#111722] p-6 sm:p-8">
                <div class="mb-6 border-b border-white/10 pb-5">
                    <h2 class="text-xl font-bold">Publishing Settings</h2>
                    <p class="mt-1 text-sm text-gray-400">
                        Control product visibility and featured status.
                    </p>
                </div>

                <div class="space-y-5">

                    <label class="flex cursor-pointer items-center gap-3">
                        <input
                            type="checkbox"
                            name="is_active"
                            value="1"
                            @checked(old('is_active'))
                            class="h-5 w-5 accent-[#D4AF37]"
                        >
                        <span>
                            <span class="block font-semibold">Active Product</span>
                            <span class="block text-xs text-gray-400">
                                Make this product available in the storefront.
                            </span>
                        </span>
                    </label>

                    <label class="flex cursor-pointer items-center gap-3">
                        <input
                            type="checkbox"
                            name="is_featured"
                            value="1"
                            @checked(old('is_featured'))
                            class="h-5 w-5 accent-[#D4AF37]"
                        >
                        <span>
                            <span class="block font-semibold">Featured Product</span>
                            <span class="block text-xs text-gray-400">
                                Mark this product as featured.
                            </span>
                        </span>
                    </label>

                </div>
            </div>

            {{-- Actions --}}
            <div class="flex flex-wrap justify-end gap-3">
                <a
                    href="{{ route('legacy-admin.products.index') }}"
                    class="rounded-lg border border-white/10 px-6 py-3 text-sm font-semibold text-gray-300 transition hover:bg-white/5"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="rounded-lg bg-[#D4AF37] px-8 py-3 text-sm font-bold text-black transition hover:bg-[#e8c65b]"
                >
                    Save Product
                </button>
            </div>

        </form>

    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const inventoryRadios = document.querySelectorAll('input[name="inventory_type"]');
    const hasVariantsInput = document.getElementById('has_variants');
    const simpleStock = document.getElementById('simple-stock');
    const variantsSection = document.getElementById('variants-section');
    const stockInput = document.getElementById('stock_quantity');
    const variantsContainer = document.getElementById('variants-container');
    const addVariantButton = document.getElementById('add-variant');
    const imageInput = document.getElementById('images');
    const imagePreview = document.getElementById('image-preview');
    const oldEnglishAltTexts = @json(array_values(old('alt_texts', [])));
    const oldArabicAltTexts = @json(array_values(old('alt_texts_ar', [])));

    let variantIndex = 0;

    function updateInventoryType() {
        const hasVariants = document.querySelector(
            'input[name="inventory_type"]:checked'
        ).value === 'variants';

        hasVariantsInput.value = hasVariants ? '1' : '0';

        simpleStock.classList.toggle('hidden', hasVariants);
        variantsSection.classList.toggle('hidden', !hasVariants);

        stockInput.disabled = hasVariants;
        stockInput.required = !hasVariants;
    }

    inventoryRadios.forEach(radio => {
        radio.addEventListener('change', updateInventoryType);
    });

    function addVariant(data = {}) {
        const index = variantIndex++;

        const row = document.createElement('div');
        row.className = 'rounded-lg border border-white/10 bg-[#080B12] p-5';

        row.innerHTML = `
            <div class="mb-4 flex items-center justify-between">
                <h4 class="font-semibold text-[#D4AF37]">Variant #${index + 1}</h4>
                <button type="button" class="remove-variant text-sm text-red-400 hover:text-red-300">
                    Remove
                </button>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">

                <div>
                    <label class="mb-2 block text-xs text-gray-400">Variant SKU *</label>
                    <input name="variants[${index}][sku]" required maxlength="100"
                        class="w-full rounded-lg border border-white/10 bg-[#111722] px-3 py-3 text-sm text-white outline-none focus:border-[#D4AF37]"
                        placeholder="AK2-SHIRT-BLK-M"
                        value="${escapeAttribute(data.sku || '')}">
                </div>

                <div>
                    <label class="mb-2 block text-xs text-gray-400">Size (English)</label>
                    <input name="variants[${index}][size]" maxlength="50"
                        class="w-full rounded-lg border border-white/10 bg-[#111722] px-3 py-3 text-sm text-white outline-none focus:border-[#D4AF37]"
                        placeholder="M / L / XL"
                        value="${escapeAttribute(data.size || '')}">
                </div>

                <div>
                    <label class="mb-2 block text-xs text-gray-400">المقاس (عربي)</label>
                    <input name="variants[${index}][translations][ar][size]" maxlength="50" dir="rtl"
                        class="w-full rounded-lg border border-white/10 bg-[#111722] px-3 py-3 text-sm text-white outline-none focus:border-[#D4AF37]"
                        value="${escapeAttribute(data.translations?.ar?.size || '')}">
                </div>

                <div>
                    <label class="mb-2 block text-xs text-gray-400">Color (English)</label>
                    <input name="variants[${index}][color]" maxlength="100"
                        class="w-full rounded-lg border border-white/10 bg-[#111722] px-3 py-3 text-sm text-white outline-none focus:border-[#D4AF37]"
                        placeholder="Black"
                        value="${escapeAttribute(data.color || '')}">
                </div>

                <div>
                    <label class="mb-2 block text-xs text-gray-400">اللون (عربي)</label>
                    <input name="variants[${index}][translations][ar][color]" maxlength="100" dir="rtl"
                        class="w-full rounded-lg border border-white/10 bg-[#111722] px-3 py-3 text-sm text-white outline-none focus:border-[#D4AF37]"
                        value="${escapeAttribute(data.translations?.ar?.color || '')}">
                </div>

                <div>
                    <label class="mb-2 block text-xs text-gray-400">Color Code</label>
                    <input name="variants[${index}][color_code]" maxlength="20"
                        class="w-full rounded-lg border border-white/10 bg-[#111722] px-3 py-3 text-sm text-white outline-none focus:border-[#D4AF37]"
                        placeholder="#000000"
                        value="${escapeAttribute(data.color_code || '')}">
                </div>

                <div>
                    <label class="mb-2 block text-xs text-gray-400">Variant Price (EGP)</label>
                    <input name="variants[${index}][price]" type="number" min="0" step="0.01"
                        class="w-full rounded-lg border border-white/10 bg-[#111722] px-3 py-3 text-sm text-white outline-none focus:border-[#D4AF37]"
                        placeholder="Leave empty to use product price"
                        value="${escapeAttribute(data.price || '')}">
                </div>

                <div>
                    <label class="mb-2 block text-xs text-gray-400">Stock Quantity *</label>
                    <input name="variants[${index}][stock_quantity]" type="number" min="0" required
                        class="w-full rounded-lg border border-white/10 bg-[#111722] px-3 py-3 text-sm text-white outline-none focus:border-[#D4AF37]"
                        placeholder="0"
                        value="${escapeAttribute(data.stock_quantity ?? 0)}">
                </div>

                <div class="sm:col-span-2 lg:col-span-3">
                    <label class="flex items-center gap-2 text-sm text-gray-300">
                        <input type="checkbox" name="variants[${index}][is_active]" value="1" checked
                            class="h-4 w-4 accent-[#D4AF37]">
                        Active Variant
                    </label>
                </div>

            </div>
        `;

        row.querySelector('.remove-variant').addEventListener('click', function () {
            row.remove();
        });

        variantsContainer.appendChild(row);
    }

    function escapeAttribute(value) {
        return String(value).replace(/[&<>"']/g, character => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        })[character]);
    }

    addVariantButton.addEventListener('click', function () {
        addVariant();
    });

    // Restore old variant values after validation errors.
    const oldVariants = @json(array_values(old('variants', [])));

    if (oldVariants.length) {
        document.querySelector('input[value="variants"][name="inventory_type"]').checked = true;

        oldVariants.forEach(variant => addVariant(variant));
    }

    updateInventoryType();

    // Image previews.
    imageInput.addEventListener('change', function () {
        imagePreview.innerHTML = '';

        const files = Array.from(this.files);

        files.forEach((file, index) => {
            const reader = new FileReader();

            reader.onload = function (event) {
                const card = document.createElement('div');
                card.className = 'overflow-hidden rounded-lg border border-white/10 bg-[#080B12]';

                const image = document.createElement('img');
                image.src = event.target.result;
                image.alt = 'Product preview';
                image.className = 'h-32 w-full object-cover';

                const label = document.createElement('p');
                label.className = 'p-2 text-center text-xs text-gray-400';
                label.textContent = index === 0 ? 'Primary Image' : `Image ${index + 1}`;

                const altTextFields = document.createElement('div');
                altTextFields.className = 'space-y-3 p-3';

                const englishAltText = document.createElement('input');
                englishAltText.name = 'alt_texts[]';
                englishAltText.type = 'text';
                englishAltText.maxLength = 255;
                englishAltText.value = oldEnglishAltTexts[index] ?? '';
                englishAltText.placeholder = 'Alt text (English)';
                englishAltText.className = 'w-full rounded-lg border border-white/10 bg-[#111722] px-3 py-2 text-sm text-white outline-none focus:border-[#D4AF37]';

                const arabicAltText = document.createElement('input');
                arabicAltText.name = 'alt_texts_ar[]';
                arabicAltText.type = 'text';
                arabicAltText.maxLength = 255;
                arabicAltText.value = oldArabicAltTexts[index] ?? '';
                arabicAltText.placeholder = 'Alt text (Arabic)';
                arabicAltText.dir = 'rtl';
                arabicAltText.className = englishAltText.className;

                altTextFields.append(englishAltText, arabicAltText);
                card.appendChild(image);
                card.appendChild(label);
                card.appendChild(altTextFields);
                imagePreview.appendChild(card);
            };

            reader.readAsDataURL(file);
        });
    });
});
</script>
@endpush
