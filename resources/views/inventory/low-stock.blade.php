@extends('layouts.app')
@section('title', 'Low Stock Alert')
@section('heading', 'Low Stock Alert')

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <a href="{{ route('inventory.low-stock', ['filter' => 'low']) }}"
           class="p-5 rounded-lg border {{ $filter === 'low' ? 'bg-ink text-white' : 'bg-white' }}">
            <p class="text-xs font-bold uppercase {{ $filter === 'low' ? 'text-gray-400' : 'text-gray-500' }}">Low Stock</p>
            <p class="font-mono text-2xl font-bold">{{ $lowCount }}</p>
        </a>
        <a href="{{ route('inventory.low-stock', ['filter' => 'out']) }}"
           class="p-5 rounded-lg border {{ $filter === 'out' ? 'bg-red-600 text-white border-red-600' : 'bg-white' }}">
            <p class="text-xs font-bold uppercase {{ $filter === 'out' ? 'text-red-100' : 'text-gray-500' }}">Out of Stock</p>
            <p class="font-mono text-2xl font-bold">{{ $outCount }}</p>
        </a>
        <a href="{{ route('inventory.low-stock', ['filter' => 'all']) }}"
           class="p-5 rounded-lg border {{ $filter === 'all' ? 'bg-brass text-white border-brass' : 'bg-white' }}">
            <p class="text-xs font-bold uppercase {{ $filter === 'all' ? 'text-white/70' : 'text-gray-500' }}">All Needing Attention</p>
            <p class="font-mono text-2xl font-bold">{{ $lowCount + $outCount }}</p>
        </a>
    </div>

    <div class="bg-white border rounded-lg divide-y">
        @forelse($items as $item)
            <div class="px-6 py-4 flex items-center justify-between gap-4">
                <div>
                    <p class="font-bold text-sm">{{ $item->name }}</p>
                    <p class="text-xs text-gray-500 font-mono">{{ $item->barcode }} · {{ $item->category }} · reorder at {{ $item->reorder_level }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-[10px] px-2 py-0.5 font-bold uppercase rounded
                        {{ $item->quantity === 0 ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700' }}">
                        {{ $item->quantity === 0 ? 'out of stock' : $item->quantity.' left' }}
                    </span>
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('purchases.requisition.index') }}" class="px-3 py-1.5 bg-ink text-white rounded text-xs font-bold uppercase">Request Restock</a>
                    @endif
                </div>
            </div>
        @empty
            <p class="text-center py-12 text-gray-500 text-sm">Nothing needs attention right now — stock levels look healthy.</p>
        @endforelse
    </div>
</div>
@endsection
