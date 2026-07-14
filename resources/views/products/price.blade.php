@extends('layouts.app')
@section('title', 'Update Price')
@section('heading', 'Update Price')

@section('content')
<div class="bg-white border rounded-lg p-6">
    <form method="GET" class="flex gap-3 mb-6">
        <input type="text" name="q" value="{{ $search }}" placeholder="Search by title or barcode..." class="flex-1 px-4 py-2.5 border rounded text-sm" autofocus>
        <button class="px-5 py-2.5 bg-ink text-white rounded text-xs font-bold uppercase">Search</button>
    </form>

    <div class="divide-y">
        @forelse($items as $item)
            <form method="POST" action="{{ route('products.price.update', $item) }}" class="py-3 flex items-center justify-between gap-4">
                @csrf
                @method('PATCH')
                <div>
                    <p class="font-bold text-sm">{{ $item->name }}</p>
                    <p class="text-xs text-gray-500 font-mono">{{ $item->barcode }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-gray-400 font-mono">৳</span>
                    <input type="number" step="0.01" name="price" value="{{ $item->price }}" class="w-28 px-3 py-2 border rounded font-mono text-right">
                    <button class="px-4 py-2 bg-ink text-white rounded text-xs font-bold uppercase">Save</button>
                </div>
            </form>
        @empty
            <p class="text-center py-12 text-gray-500 text-sm">{{ $search ? 'No matches found.' : 'Search for a product to update its price.' }}</p>
        @endforelse
    </div>
</div>
@endsection
