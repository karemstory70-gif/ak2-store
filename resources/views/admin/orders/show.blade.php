@extends('layouts.store')

@section('title', 'Order Details | AK2')

@section('content')
<section class="min-h-screen bg-[#080B12] py-10 text-white">
    <div class="mx-auto max-w-5xl px-4">

        <a href="{{ route('legacy-admin.orders.index') }}"
           class="text-sm text-[#D4AF37] hover:underline">
            ← Back to Orders
        </a>

        <div class="mb-8 mt-5 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold">Order Details</h1>
                <p class="mt-2 text-[#D4AF37]">{{ $order->order_number }}</p>
            </div>
            <span class="rounded-lg border border-white/10 px-4 py-2">
                {{ ucfirst($order->status) }}
            </span>
        </div>

        @if(session('success'))
            <div class="mb-6 rounded-lg border border-green-500/30 bg-green-500/10 p-4 text-green-400">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid gap-6 md:grid-cols-2">

            <div class="rounded-xl border border-white/10 bg-[#111722] p-6">
                <h2 class="mb-5 text-xl font-bold">Customer Information</h2>

                <p class="mb-3"><span class="text-gray-400">Name:</span> {{ $order->customer_name }}</p>
                <p class="mb-3"><span class="text-gray-400">Email:</span> {{ $order->customer_email ?: '—' }}</p>
                <p class="mb-3"><span class="text-gray-400">Phone:</span> {{ $order->customer_phone }}</p>
                <p class="mb-3"><span class="text-gray-400">Address:</span> {{ $order->shipping_address }}</p>
                <p class="mb-3"><span class="text-gray-400">City:</span> {{ $order->shipping_city }}</p>
                <p><span class="text-gray-400">Country:</span> {{ $order->shipping_country }}</p>
            </div>

            <div class="rounded-xl border border-white/10 bg-[#111722] p-6">
                <h2 class="mb-5 text-xl font-bold">Payment Summary</h2>

                <p class="mb-3">Subtotal: {{ number_format($order->subtotal, 2) }} EGP</p>
                <p class="mb-3">Discount: {{ number_format($order->discount_amount, 2) }} EGP</p>
                <p class="mb-3">Shipping: {{ number_format($order->shipping_amount, 2) }} EGP</p>
                <p class="mb-3">Tax: {{ number_format($order->tax_amount, 2) }} EGP</p>

                <hr class="my-4 border-white/10">

                <p class="text-xl font-bold text-[#D4AF37]">
                    Total: {{ number_format($order->total, 2) }} EGP
                </p>

                <p class="mt-4">Method: {{ $order->payment_method }}</p>
                <p class="mt-2">Payment Status: {{ ucfirst($order->payment_status) }}</p>
            </div>

        </div>

        <div class="mt-6 rounded-xl border border-white/10 bg-[#111722] p-6">
            <h2 class="mb-5 text-xl font-bold">Ordered Products</h2>

            <div class="space-y-5">
                @foreach($order->items as $item)
                    <div class="flex flex-wrap items-center justify-between gap-4 border-b border-white/10 pb-5">
                        <div>
                            <p class="font-semibold">{{ $item->product_name }}</p>
                            <p class="mt-1 text-sm text-gray-400">
                                SKU: {{ $item->product_sku ?: '—' }}
                            </p>
                            @if($item->variant_details)
                                <p class="mt-1 text-sm text-gray-400">
                                    {{ $item->variant_details }}
                                </p>
                            @endif
                            <p class="mt-1 text-sm text-gray-400">
                                Quantity: {{ $item->quantity }}
                            </p>
                        </div>

                        <p class="font-semibold">
                            {{ number_format($item->total, 2) }} EGP
                        </p>
                    </div>
                @endforeach
            </div>
        </div>

        <form method="POST"
              action="{{ route('legacy-admin.orders.update', $order) }}"
              class="mt-6 rounded-xl border border-white/10 bg-[#111722] p-6">
            @csrf
            @method('PATCH')

            <h2 class="mb-5 text-xl font-bold">Update Order</h2>

            @if($errors->any())
                <div class="mb-5 rounded-lg bg-red-500/10 p-4 text-red-400">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="grid gap-5 md:grid-cols-2">

                <div>
                    <label class="mb-2 block text-sm text-gray-400">Order Status</label>
                    <select name="status"
                            class="w-full rounded-lg border border-white/10 bg-[#080B12] p-3">
                        @foreach(['pending', 'processing', 'shipped', 'delivered', 'cancelled'] as $status)
                            <option value="{{ $status }}" @selected(old('status', $order->status) === $status)>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-sm text-gray-400">Payment Status</label>
                    <select name="payment_status"
                            class="w-full rounded-lg border border-white/10 bg-[#080B12] p-3">
                        @foreach(['pending', 'paid', 'failed', 'refunded'] as $status)
                            <option value="{{ $status }}" @selected(old('payment_status', $order->payment_status) === $status)>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>

            <div class="mt-5">
                <label class="mb-2 block text-sm text-gray-400">Admin Notes</label>
                <textarea name="admin_notes" rows="4"
                          class="w-full rounded-lg border border-white/10 bg-[#080B12] p-3">{{ old('admin_notes', $order->admin_notes) }}</textarea>
            </div>

            <button type="submit"
                    class="mt-6 rounded-lg bg-[#D4AF37] px-7 py-3 font-bold text-black hover:bg-[#e8c65b]">
                Save Changes
            </button>
        </form>

    </div>
</section>
@endsection
