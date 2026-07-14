@extends('layouts.app')
@section('title', 'Products')
@section('heading', 'List Products')

@section('content')
<div class="bg-white border rounded-lg">
    <div class="flex items-center justify-between px-6 pt-6 pb-4 gap-4 flex-wrap">
        <h3 class="font-bold text-sm">Active Stock Database <span class="text-gray-400 font-normal">({{ $items->count() }} items)</span></h3>
        @if(auth()->user()->isAdmin())
            <a href="{{ route('products.create') }}" class="bg-ink text-white px-4 py-2 text-xs font-bold uppercase rounded">+ Add Product</a>
        @endif
    </div>

    <form method="GET" class="px-6 mb-4 flex gap-3 flex-wrap items-center">
        <input type="text" name="q" value="{{ $search }}" placeholder="Search by title or barcode..."
               class="flex-1 min-w-[200px] px-4 py-2 border rounded text-sm">
        <label class="flex items-center gap-2 text-xs font-bold uppercase text-gray-500">
            <input type="checkbox" name="archived" value="1" {{ $showArchived ? 'checked' : '' }} onchange="this.form.submit()"> Show archived
        </label>
        <button class="px-4 py-2 border rounded text-xs font-bold uppercase">Search</button>
    </form>

    <div class="divide-y">
        @forelse($items as $item)
            <div class="px-6 py-4 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 {{ $item->status === 'archived' ? 'opacity-50' : '' }}">
                <div>
                    <p class="font-bold text-sm">{{ $item->name }}</p>
                    <p class="text-xs text-gray-500 font-mono mt-0.5">{{ $item->barcode }} · {{ $item->category }}{{ $item->brand ? ' · '.$item->brand : '' }} · {{ $item->unit }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="text-right">
                        <p class="font-mono font-bold text-sm">৳{{ number_format($item->price, 2) }}</p>
                        <span class="text-[10px] px-2 py-0.5 font-bold uppercase rounded
                            {{ $item->status === 'archived' ? 'bg-gray-100 text-gray-500' : ($item->isLowStock() || $item->quantity === 0 ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700') }}">
                            {{ $item->status === 'archived' ? 'archived' : $item->quantity.' in stock' }}
                        </span>
                    </div>
                    @if(auth()->user()->isAdmin())
                        <div class="flex gap-1">
                            @if($item->status === 'archived')
                                <form method="POST" action="{{ route('products.restore', $item) }}">
                                    @csrf
                                    <button class="w-8 h-8 border rounded text-green-600 text-xs">↺</button>
                                </form>
                            @else
                                <a href="{{ route('products.edit', $item) }}" class="w-8 h-8 flex items-center justify-center border rounded text-xs">✎</a>
                                <form method="POST" action="{{ route('products.archive', $item) }}" onsubmit="return confirm('Archive {{ $item->name }}?')">
                                    @csrf
                                    <button class="w-8 h-8 border rounded text-red-500 text-xs">🗄</button>
                                </form>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <p class="text-center py-12 text-gray-500 text-sm">No items found.</p>
        @endforelse
    </div>
</div>
@endsection
