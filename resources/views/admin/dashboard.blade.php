@extends('layouts.store')

@section('title', 'Admin Dashboard | AK2')

@section('content')
<section class="min-h-screen bg-[#080B12] py-10 text-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        <div class="mb-10 flex flex-wrap items-center justify-between gap-4">
            <div>
                <p class="mb-2 text-sm tracking-[0.3em] text-[#D4AF37]">
                    AK2 TECHNOLOGIES
                </p>
                <h1 class="text-3xl font-bold">Dashboard</h1>
                <p class="mt-2 text-gray-400">
                    Store performance and business overview.
                </p>
            </div>

            <a href="{{ route('legacy-admin.orders.index') }}"
               class="rounded-lg bg-[#D4AF37] px-5 py-3 font-bold text-black transition hover:bg-[#e8c65b]">
                Manage Orders
            </a>
        </div>

        {{-- Statistics --}}
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-5">

            @php
                $cards = [
                    ['label' => 'Total Orders', 'value' => number_format($stats['orders'])],
                    ['label' => 'Pending Orders', 'value' => number_format($stats['pending_orders'])],
                    ['label' => 'Paid Revenue', 'value' => number_format($stats['revenue'], 2) . ' EGP'],
                    ['label' => 'Customers', 'value' => number_format($stats['customers'])],
                    ['label' => 'Active Products', 'value' => number_format($stats['products'])],
                ];
            @endphp

            @foreach($cards as $card)
                <div class="rounded-xl border border-white/10 bg-[#111722] p-5">
                    <p class="text-sm text-gray-400">{{ $card['label'] }}</p>
                    <p class="mt-4 text-2xl font-bold text-[#D4AF37]">
                        {{ $card['value'] }}
                    </p>
                </div>
            @endforeach

        </div>

        {{-- Latest Orders --}}
        <div class="mt-10 overflow-hidden rounded-xl border border-white/10 bg-[#111722]">

            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-white/10 p-6">
                <div>
                    <h2 class="text-xl font-bold">Latest Orders</h2>
                    <p class="mt-1 text-sm text-gray-400">
                        The latest orders received by your store.
                    </p>
                </div>

                <a href="{{ route('legacy-admin.orders.index') }}"
                   class="text-sm font-semibold text-[#D4AF37] hover:underline">
                    View All Orders
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-white/5 text-gray-400">
                        <tr>
                            <th class="px-6 py-4">Order</th>
                            <th class="px-6 py-4">Customer</th>
                            <th class="px-6 py-4">Total</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Payment</th>
                            <th class="px-6 py-4">Date</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-white/10">
                        @forelse($latestOrders as $order)
                            <tr class="hover:bg-white/[0.03]">
                                <td class="px-6 py-4">
                                    <a href="{{ route('legacy-admin.orders.show', $order) }}"
                                       class="font-semibold text-[#D4AF37] hover:underline">
                                        {{ $order->order_number }}
                                    </a>
                                    <p class="mt-1 text-xs text-gray-500">
                                        {{ $order->items_count }} items
                                    </p>
                                </td>

                                <td class="px-6 py-4">
                                    {{ $order->customer_name }}
                                </td>

                                <td class="px-6 py-4 font-semibold">
                                    {{ number_format($order->total, 2) }} EGP
                                </td>

                                <td class="px-6 py-4">
                                    {{ ucfirst($order->status) }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ ucfirst($order->payment_status) }}
                                </td>

                                <td class="px-6 py-4 text-gray-400">
                                    {{ $order->placed_at?->format('d/m/Y') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-gray-400">
                                    No orders have been placed yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</section>
@endsection
