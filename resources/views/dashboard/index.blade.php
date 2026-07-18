@extends('layouts.app')
@section('title', 'Overview')
@section('heading', 'Overview')

@section('content')
<div class="space-y-6">
    @if($weather || $timezone || $currency)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            @if($weather)
                <div class="bg-[#F7F5EC] border rounded-lg px-5 py-4 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        @if($weather['icon'])
                            <img src="https://openweathermap.org/img/wn/{{ $weather['icon'] }}@2x.png" alt="{{ $weather['description'] }}" class="w-11 h-11 -my-2">
                        @endif
                        <div>
                            <p class="font-bold text-sm">{{ $weather['city'] }}</p>
                            <p class="text-xs text-ink/50">{{ $weather['description'] }} · {{ $weather['humidity'] }}% humidity</p>
                        </div>
                    </div>
                    <p class="font-mono text-xl font-bold">{{ $weather['temp'] }}°{{ $weather['units'] === 'metric' ? 'C' : 'F' }}</p>
                </div>
            @endif

            @if($timezone)
                <div class="bg-[#F7F5EC] border rounded-lg px-5 py-4">
                    <p class="text-xs font-bold uppercase text-ink/50 mb-1">Local Time — {{ $timezone['zone'] }}</p>
                    <p class="font-mono text-xl font-bold">{{ $timezone['formatted'] }}</p>
                    @if($timezone['abbreviation'])
                        <p class="text-xs text-ink/50 mt-1">{{ $timezone['abbreviation'] }}{{ $timezone['gmt_offset_hours'] !== null ? ' · GMT'.($timezone['gmt_offset_hours'] >= 0 ? '+' : '').$timezone['gmt_offset_hours'] : '' }}</p>
                    @endif
                </div>
            @endif

            @if($currency)
                <div class="bg-[#F7F5EC] border rounded-lg px-5 py-4">
                    <p class="text-xs font-bold uppercase text-ink/50 mb-1">Exchange Rate — 1 {{ $currency['base'] }}</p>
                    <div class="flex flex-wrap gap-x-4 gap-y-1">
                        @foreach($currency['rates'] as $code => $value)
                            <p class="font-mono text-sm"><span class="text-ink/40">{{ $code }}</span> <span class="font-bold">{{ number_format($value, 2) }}</span></p>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    @endif

    {{-- Signature: today's headline numbers, styled as garment swing tags --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="swing-tag p-7 pt-8">
            <p class="text-xs font-bold uppercase tracking-wider text-ink/50 mb-2 ml-4">Today's Revenue</p>
            <p class="font-display text-4xl font-extrabold text-indigo ml-4">৳{{ number_format($todayRevenue, 0) }}</p>
            @if($todayRevenueUsd !== null)
                <p class="text-sm font-mono text-ink/50 ml-4 mt-1">≈ ${{ number_format($todayRevenueUsd, 2) }}</p>
            @endif
            <div class="swing-tag-barcode mt-4"></div>
        </div>
        <div class="swing-tag p-7 pt-8">
            <p class="text-xs font-bold uppercase tracking-wider text-ink/50 mb-2 ml-4">Items Sold Today</p>
            <p class="font-display text-4xl font-extrabold ml-4">{{ $todayItemsSold }} <span class="text-sm text-ink/50 font-sans font-normal">pieces</span></p>
            <div class="swing-tag-barcode mt-4"></div>
        </div>
        <a href="{{ route('inventory.low-stock') }}" class="swing-tag p-7 pt-8 block {{ $lowStockItems->count() > 0 ? 'border-[#D9B3A8]' : '' }}">
            <p class="text-xs font-bold uppercase tracking-wider mb-2 ml-4 {{ $lowStockItems->count() > 0 ? 'text-rust' : 'text-ink/50' }}">Low Stock Alerts</p>
            <p class="font-display text-4xl font-extrabold ml-4 {{ $lowStockItems->count() > 0 ? 'text-rust' : '' }}">{{ $lowStockItems->count() }}
                <span class="text-sm text-ink/50 font-sans font-normal">{{ $outOfStockCount }} out of stock</span></p>
            <div class="swing-tag-barcode mt-4"></div>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white border rounded-lg">
            <div class="flex items-center justify-between px-6 pt-5 pb-4">
                <h3 class="font-display text-base font-bold uppercase tracking-wide">Needs Restocking</h3>
                <a href="{{ route('inventory.low-stock') }}" class="text-xs font-bold text-brass">Manage Stock</a>
            </div>
            <div class="divide-y">
                @forelse($lowStockItems as $item)
                    <div class="px-6 py-3 flex justify-between items-center">
                        <div>
                            <p class="font-bold text-sm">{{ $item->name }}</p>
                            <p class="text-xs text-ink/50 font-mono">{{ $item->barcode }}</p>
                        </div>
                        <span class="text-[10px] px-2 py-0.5 font-bold uppercase bg-[#FBF1EF] text-rust rounded">{{ $item->quantity }} left</span>
                    </div>
                @empty
                    <p class="px-6 py-8 text-sm text-ink/50 text-center">All active items are comfortably stocked.</p>
                @endforelse
            </div>
        </div>

        @if(auth()->user()->isAdmin())
        <div class="bg-white border rounded-lg">
            <div class="px-6 pt-5 pb-4">
                <h3 class="font-display text-base font-bold uppercase tracking-wide">Top Sellers (All Time)</h3>
            </div>
            <div class="divide-y">
                @forelse($topSellers as $i => $item)
                    <div class="px-6 py-3 flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <span class="font-mono text-xs font-bold text-ink/40 w-4">{{ str_pad($i+1, 2, '0', STR_PAD_LEFT) }}</span>
                            <div>
                                <p class="font-bold text-sm">{{ $item['name'] }}</p>
                                <p class="text-xs text-ink/50 font-mono">{{ $item['units_sold'] }} sold</p>
                            </div>
                        </div>
                        <p class="font-mono text-sm font-bold">৳{{ number_format($item['revenue']) }}</p>
                    </div>
                @empty
                    <p class="px-6 py-8 text-sm text-ink/50 text-center">No completed sales yet.</p>
                @endforelse
            </div>
        </div>
        @else
        <div class="bg-white border rounded-lg flex items-center justify-center p-8 text-sm text-ink/40 text-center">
            Full sales leaderboard and financial reports are visible to Admin accounts.
        </div>
        @endif
    </div>

    <div class="bg-white border rounded-lg">
        <div class="flex items-center justify-between px-6 pt-5 pb-4">
            <h3 class="font-display text-base font-bold uppercase tracking-wide">Recent Activity</h3>
            <a href="{{ route('sales.all') }}" class="text-xs font-bold text-brass">Full Ledger</a>
        </div>
        <div class="divide-y">
            @forelse($recentSales as $sale)
                <div class="px-6 py-3 flex justify-between items-center">
                    <div>
                        <p class="font-bold text-sm {{ $sale->status === 'refunded' ? 'line-through text-ink/40' : '' }}">{{ $sale->product?->name }}</p>
                        <p class="text-xs text-ink/50 font-mono">{{ $sale->sold_at->format('Y-m-d H:i') }}</p>
                    </div>
                    <p class="font-mono text-sm font-bold {{ $sale->status === 'refunded' ? 'line-through text-ink/40' : '' }}">৳{{ number_format($sale->amount_paid) }}</p>
                </div>
            @empty
                <p class="px-6 py-8 text-sm text-ink/50 text-center">No transactions recorded yet.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
