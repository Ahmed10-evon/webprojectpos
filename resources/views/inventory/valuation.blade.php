@extends('layouts.app')
@section('title', 'Inventory Valuation')
@section('heading', 'Inventory Valuation')

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div class="bg-ink text-white p-6 rounded-lg">
            <p class="text-xs font-bold uppercase text-gray-400 mb-2">Total Stock Value</p>
            <p class="font-mono text-2xl font-bold">৳{{ number_format($totalValue, 2) }}</p>
        </div>
        <div class="bg-white border p-6 rounded-lg">
            <p class="text-xs font-bold uppercase text-gray-500 mb-2">Total Units on Hand</p>
            <p class="font-mono text-2xl font-bold">{{ number_format($totalUnits) }}</p>
        </div>
    </div>

    <div class="bg-white border rounded-lg p-6">
        <h3 class="font-bold text-sm mb-4">Value by Category</h3>
        <div class="divide-y">
            @forelse($byCategory as $category => $data)
                <div class="py-3 flex items-center justify-between">
                    <p class="font-bold text-sm">{{ $category ?: 'Uncategorized' }}</p>
                    <div class="text-right">
                        <p class="font-mono font-bold text-sm">৳{{ number_format($data['value'], 2) }}</p>
                        <p class="text-xs text-gray-500">{{ $data['units'] }} units</p>
                    </div>
                </div>
            @empty
                <p class="text-center py-8 text-gray-500 text-sm">No stock to value yet.</p>
            @endforelse
        </div>
    </div>

    <div class="bg-white border rounded-lg divide-y">
        <h3 class="font-bold text-sm px-6 pt-5 pb-3">Per-Item Breakdown</h3>
        @forelse($products as $product)
            <div class="px-6 py-3 flex items-center justify-between gap-4">
                <div>
                    <p class="font-bold text-sm">{{ $product->name }}</p>
                    <p class="text-xs text-gray-500 font-mono">{{ $product->quantity }} x ৳{{ number_format($product->price, 2) }}</p>
                </div>
                <p class="font-mono font-bold text-sm">৳{{ number_format($product->stock_value, 2) }}</p>
            </div>
        @empty
            <p class="text-center py-12 text-gray-500 text-sm">No active products yet.</p>
        @endforelse
    </div>
</div>
@endsection
