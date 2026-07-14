@extends('layouts.app')
@section('title', 'Add Sale')
@section('heading', 'Add Sale')

@section('content')
<div class="bg-white border rounded-lg p-6">
    <form method="GET" class="mb-6">
        <input type="text" name="q" value="{{ $search }}" placeholder="Search by item name or barcode..." class="w-full px-4 py-2.5 border rounded text-sm" autofocus>
    </form>

    <div class="divide-y">
        @forelse($results as $item)
            <form method="POST" action="{{ route('sales.add.store') }}" class="py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                @csrf
                <input type="hidden" name="product_id" value="{{ $item->id }}">
                <div>
                    <p class="font-bold text-sm">{{ $item->name }}</p>
                    <p class="text-xs text-gray-500 font-mono">{{ $item->barcode }} · ৳{{ number_format($item->price, 2) }} · {{ $item->quantity }} in stock</p>
                </div>
                <div class="flex items-center gap-2">
                    <select name="payment_method" class="px-3 py-2 border rounded text-sm">
                        <option value="cash">CASH</option>
                        <option value="bkash">BKASH</option>
                        <option value="nagad">NAGAD</option>
                        <option value="upay">UPAY</option>
                        <option value="rocket">ROCKET</option>
                        <option value="bank/card">BANK/CARD</option>
                    </select>
                    <input name="trx_id" placeholder="Trx ID" class="w-28 px-3 py-2 border rounded text-sm font-mono">
                    <button class="px-4 py-2 bg-ink text-white rounded text-xs font-bold uppercase">Sell</button>
                </div>
            </form>
        @empty
            <p class="text-center py-12 text-gray-500 text-sm">{{ $search ? 'No matches found.' : 'Search to find an item to sell.' }}</p>
        @endforelse
    </div>
</div>
@endsection
