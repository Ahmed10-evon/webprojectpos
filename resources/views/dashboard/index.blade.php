@extends('layouts.app')
@section('title', 'Overview')
@section('heading', 'Overview')

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="bg-ink p-7 text-white rounded-lg">
            <p class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-2">Today's Revenue</p>
            <p class="font-mono text-3xl font-bold">৳{{ number_format($todayRevenue, 0) }}</p>
        </div>
        <div class="bg-white p-7 border rounded-lg">
            <p class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Items Sold Today</p>
            <p class="font-mono text-3xl font-bold">{{ $todayItemsSold }} <span class="text-sm text-gray-500 font-sans">pieces</span></p>
        </div>
        <a href="{{ route('products.index') }}" class="p-7 border rounded-lg block {{ $lowStockItems->count() > 0 ? 'bg-red-50 border-red-200' : 'bg-white' }}">
            <p class="text-xs font-bold uppercase tracking-wider mb-2 {{ $lowStockItems->count() > 0 ? 'text-red-600' : 'text-gray-500' }}">Low Stock Alerts</p>
            <p class="font-mono text-3xl font-bold {{ $lowStockItems->count() > 0 ? 'text-red-600' : '' }}">{{ $lowStockItems->count() }}
                <span class="text-sm text-gray-500 font-sans">{{ $outOfStockCount }} out of stock</span></p>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white border rounded-lg">
            <div class="flex items-center justify-between px-6 pt-5 pb-4">
                <h3 class="font-bold text-sm">Needs Restocking</h3>
                <a href="{{ route('products.index') }}" class="text-xs font-bold text-brass">Manage Stock</a>
            </div>
            <div class="divide-y">
                @forelse($lowStockItems as $item)
                    <div class="px-6 py-3 flex justify-between items-center">
                        <div>
                            <p class="font-bold text-sm">{{ $item->name }}</p>
                            <p class="text-xs text-gray-500 font-mono">{{ $item->barcode }}</p>
                        </div>
                        <span class="text-[10px] px-2 py-0.5 font-bold uppercase bg-red-100 text-red-700 rounded">{{ $item->quantity }} left</span>
                    </div>
                @empty
                    <p class="px-6 py-8 text-sm text-gray-500 text-center">All active items are comfortably stocked.</p>
                @endforelse
            </div>
        </div>

        @if(auth()->user()->isAdmin())
        <div class="bg-white border rounded-lg">
            <div class="px-6 pt-5 pb-4">
                <h3 class="font-bold text-sm">Top Sellers (All Time)</h3>
            </div>
            <div class="divide-y">
                @forelse($topSellers as $i => $item)
                    <div class="px-6 py-3 flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <span class="font-mono text-xs font-bold text-gray-400 w-4">{{ str_pad($i+1, 2, '0', STR_PAD_LEFT) }}</span>
                            <div>
                                <p class="font-bold text-sm">{{ $item['name'] }}</p>
                                <p class="text-xs text-gray-500 font-mono">{{ $item['units_sold'] }} sold</p>
                            </div>
                        </div>
                        <p class="font-mono text-sm font-bold">৳{{ number_format($item['revenue']) }}</p>
                    </div>
                @empty
                    <p class="px-6 py-8 text-sm text-gray-500 text-center">No completed sales yet.</p>
                @endforelse
            </div>
        </div>
        @else
        <div class="bg-white border rounded-lg flex items-center justify-center p-8 text-sm text-gray-400 text-center">
            Full sales leaderboard and financial reports are visible to Admin accounts.
        </div>
        @endif
    </div>

    <div class="bg-white border rounded-lg">
        <div class="flex items-center justify-between px-6 pt-5 pb-4">
            <h3 class="font-bold text-sm">Recent Activity</h3>
            <a href="{{ route('sales.all') }}" class="text-xs font-bold text-brass">Full Ledger</a>
        </div>
        <div class="divide-y">
            @forelse($recentSales as $sale)
                <div class="px-6 py-3 flex justify-between items-center">
                    <div>
                        <p class="font-bold text-sm {{ $sale->status === 'refunded' ? 'line-through text-gray-400' : '' }}">{{ $sale->product?->name }}</p>
                        <p class="text-xs text-gray-500 font-mono">{{ $sale->sold_at->format('Y-m-d H:i') }}</p>
                    </div>
                    <p class="font-mono text-sm font-bold {{ $sale->status === 'refunded' ? 'line-through text-gray-400' : '' }}">৳{{ number_format($sale->amount_paid) }}</p>
                </div>
            @empty
                <p class="px-6 py-8 text-sm text-gray-500 text-center">No transactions recorded yet.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
