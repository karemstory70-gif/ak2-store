@extends('layouts.store')

@section('title', 'Orders Management | AK2')

@section('content')
<section class="min-h-screen bg-[#080B12] py-10 text-white">
    <div class="mx-auto max-w-7xl px-4">

        <div class="mb-8">
            <a href="{{ route('legacy-admin.dashboard') }}"
               class="text-sm text-[#D4AF37] hover:underline">
                ← Dashboard
            </a>
            <h1 class="mt-4 text-3xl font-bold">Orders Management</h1>
            <p class="mt-2 text-gray-400">Manage customer orders and payments.</p>
        </div>

        @if(session('success'))
            <div class="mb-6 rounded-lg border border-green-500/30 bg-green-500/10 p-4 text-green-400">
                {{ session('success') }}
            </div>
        @endif

        <form method="GET" class="mb-6 flex flex-wrap gap-3">
            <input
                type="search"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search order, customer or phone..."
                class="min-w-[240px] flex-1 rounded-lg border border-white/10 bg-[#111722] px-4 py-3 text-white"
            >

            <select name="status"
                    class="rounded-lg border border-white/10 bg-[#111722] px-4 py-3 text-white">
                <option value="">All statuses</option>
                @foreach(['pending', 'processing', 'shipped', 'delivered', 'cancelled'] as $status)
                    <option value="{{ $status }}" @selected(request('status') === $status)>
                        {{ ucfirst($status) }}
                    </option>
                @endforeach
            </select>

            <button class="rounded-lg bg-[#D4AF37] px-6 py-3 font-bold text-black">
                Filter
            </button>

            <a href="{{ route('legacy-admin.orders.index') }}"
               class="rounded-lg border border-white/20 px-5 py-3">
                Reset
            </a>
        </form>

        <div class="overflow-x-auto rounded-xl border border-white/10 bg-[#111722]">
            <table class="w-full text-left text-sm">
                <thead class="bg-white/5 text-gray-400">
                    <tr>
                        <th class="px-5 py-4">Order</th>
                        <th class="px-5 py-4">Customer</th>
                        <th class="px-5 py-4">Items</th>
                        <th class="px-5 py-4">Total</th>
                        <th class="px-5 py-4">Status</th>
                        <th class="px-5 py-4">Payment</th>
                        <th class="px-5 py-4">Date</th>
                        <th class="px-5 py-4">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-white/10">
                    @forelse($orders as $order)
                        <tr class="hover:bg-white/[0.03]">
                            <td class="px-5 py-4 font-semibold text-[#D4AF37]">
                                {{ $order->order_number }}
                            </td>
                            <td class="px-5 py-4">
                                {{ $order->customer_name }}
                                <p class="mt-1 text-xs text-gray-500">
                                    {{ $order->customer_phone }}
                                </p>
                            </td>
                            <td class="px-5 py-4">{{ $order->items_count }}</td>
                            <td class="px-5 py-4">
                                {{ number_format($order->total, 2) }} EGP
                            </td>
                            <td class="px-5 py-4">{{ ucfirst($order->status) }}</td>
                            <td class="px-5 py-4">{{ ucfirst($order->payment_status) }}</td>
                            <td class="px-5 py-4 text-gray-400">
                                {{ $order->placed_at?->format('d/m/Y') }}
                            </td>
                            <td class="px-5 py-4">
                                <a href="{{ route('legacy-admin.orders.show', $order) }}"
                                   class="font-semibold text-[#D4AF37] hover:underline">
                                    View Details
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-12 text-center text-gray-400">
                                No orders found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $orders->links() }}
        </div>

    </div>
</section>
@endsection
