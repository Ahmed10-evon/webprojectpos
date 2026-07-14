@extends('layouts.app')
@section('title', 'Purchase Returns')
@section('heading', 'Purchase Returns')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white border rounded-lg p-6">
        <form method="GET" class="flex gap-3 mb-6">
            <input name="barcode" value="{{ $barcode }}" placeholder="Scan or type barcode..." class="flex-1 px-4 py-2.5 border rounded text-sm font-mono" autofocus>
            <button class="px-5 py-2.5 bg-ink text-white rounded text-xs font-bold uppercase">Find</button>
        </form>

        @if($match)
            <form method="POST" action="{{ route('purchases.returns.store') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="product_id" value="{{ $match->id }}">
                <div class="bg-gray-50 border rounded p-4">
                    <p class="font-bold text-sm">{{ $match->name }}</p>
                    <p class="text-xs text-gray-500 font-mono">{{ $match->barcode }} · Currently {{ $match->quantity }} in stock</p>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-500 mb-2">Quantity Returned</label>
                    <input required type="number" name="quantity" min="1" max="{{ $match->quantity }}" value="1" class="w-full px-4 py-2.5 border rounded font-mono">
                </div>
                <input name="supplier_name" placeholder="Returned to (supplier)" class="w-full px-4 py-2.5 border rounded text-sm">
                <textarea name="reason" placeholder="Reason for return" class="w-full px-4 py-2.5 border rounded text-sm" rows="2"></textarea>
                <button class="w-full bg-red-600 text-white py-3 rounded text-sm font-bold uppercase">Log Return</button>
            </form>
        @else
            <p class="text-center py-8 text-gray-500 text-sm">Search a barcode to log a return to supplier.</p>
        @endif
    </div>

    <div class="bg-white border rounded-lg divide-y">
        @forelse($returns as $r)
            <div class="px-6 py-4">
                <p class="font-bold text-sm">{{ $r->item_name }} <span class="text-gray-400 font-normal">x{{ $r->quantity }}</span></p>
                <p class="text-xs text-gray-500">{{ $r->returned_at->format('Y-m-d H:i') }} {{ $r->reason ? '· '.$r->reason : '' }}</p>
            </div>
        @empty
            <p class="text-center py-12 text-gray-500 text-sm">No returns logged yet.</p>
        @endforelse
    </div>
</div>
@endsection
