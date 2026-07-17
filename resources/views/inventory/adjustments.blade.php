@extends('layouts.app')
@section('title', 'Stock Adjustment')
@section('heading', 'Stock Adjustment')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white border rounded-lg p-6">
        <h3 class="font-bold text-sm mb-1">Adjust Stock</h3>
        <p class="text-xs text-gray-500 mb-4">For damage, theft, expiry, or correcting a physical recount — anything that isn't a sale or a purchase.</p>

        <form method="GET" class="flex gap-3 mb-5">
            <input type="text" name="q" value="{{ $search }}" placeholder="Search by name or barcode..." class="flex-1 px-4 py-2.5 border rounded text-sm" autofocus>
            <button class="px-5 py-2.5 bg-ink text-white rounded text-xs font-bold uppercase">Find</button>
        </form>

        <div class="divide-y">
            @forelse($matches as $product)
                <details class="py-3 group">
                    <summary class="flex items-center justify-between cursor-pointer list-none">
                        <div>
                            <p class="font-bold text-sm">{{ $product->name }}</p>
                            <p class="text-xs text-gray-500 font-mono">{{ $product->barcode }} · currently {{ $product->quantity }} in stock</p>
                        </div>
                        <span class="text-xs text-brass font-bold uppercase group-open:hidden">Adjust</span>
                    </summary>
                    <form method="POST" action="{{ route('inventory.adjustments.store') }}" class="mt-3 space-y-3 bg-gray-50 border rounded p-4">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <div class="grid grid-cols-2 gap-3">
                            <select name="direction" class="px-3 py-2 border rounded text-sm">
                                <option value="decrease">Decrease (remove stock)</option>
                                <option value="increase">Increase (add stock)</option>
                            </select>
                            <input required type="number" min="1" name="quantity" placeholder="Quantity" class="px-3 py-2 border rounded text-sm font-mono">
                        </div>
                        <select required name="reason" class="w-full px-3 py-2 border rounded text-sm">
                            <option value="" disabled selected>Reason...</option>
                            @foreach($reasons as $reason)
                                <option value="{{ $reason }}">{{ ucfirst(str_replace('_', ' ', $reason)) }}</option>
                            @endforeach
                        </select>
                        <input name="notes" placeholder="Note (optional)" class="w-full px-3 py-2 border rounded text-sm">
                        <button class="w-full bg-ink text-white py-2 rounded text-xs font-bold uppercase">Apply Adjustment</button>
                    </form>
                </details>
            @empty
                <p class="text-center py-8 text-gray-500 text-sm">{{ $search ? 'No matches found.' : 'Search a product to adjust its stock.' }}</p>
            @endforelse
        </div>
    </div>

    <div class="bg-white border rounded-lg divide-y">
        <h3 class="font-bold text-sm px-6 pt-5 pb-3">Recent Adjustments</h3>
        @forelse($recent as $adj)
            <div class="px-6 py-3 flex items-center justify-between gap-4">
                <div>
                    <p class="font-bold text-sm">{{ $adj->product?->name ?? 'Deleted product' }}</p>
                    <p class="text-xs text-gray-500">{{ ucfirst(str_replace('_', ' ', $adj->reason)) }} · {{ $adj->adjusted_at->format('Y-m-d H:i') }}
                        @if($adj->user) · by {{ $adj->user->name }} @endif</p>
                </div>
                <span class="text-xs font-mono font-bold {{ $adj->direction === 'increase' ? 'text-green-600' : 'text-red-600' }}">
                    {{ $adj->direction === 'increase' ? '+' : '−' }}{{ $adj->quantity }}
                </span>
            </div>
        @empty
            <p class="text-center py-12 text-gray-500 text-sm">No adjustments logged yet.</p>
        @endforelse
    </div>
</div>
@endsection
