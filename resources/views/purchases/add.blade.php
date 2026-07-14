@extends('layouts.app')
@section('title', 'Add Purchase')
@section('heading', 'Add Purchase (Receive Stock)')

@section('content')
<div class="max-w-xl bg-white border rounded-lg p-6">
    <form method="GET" class="flex gap-3 mb-6">
        <input name="barcode" value="{{ $barcode }}" placeholder="Scan or type product barcode..." class="flex-1 px-4 py-2.5 border rounded text-sm font-mono" autofocus>
        <button class="px-5 py-2.5 bg-ink text-white rounded text-xs font-bold uppercase">Find</button>
    </form>

    @if($match)
        <form method="POST" action="{{ route('purchases.add.store') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="product_id" value="{{ $match->id }}">
            <div class="bg-gray-50 border rounded p-4">
                <p class="font-bold text-sm">{{ $match->name }}</p>
                <p class="text-xs text-gray-500 font-mono">{{ $match->barcode }} · Currently {{ $match->quantity }} in stock</p>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-500 mb-2">Quantity Received</label>
                    <input required type="number" name="quantity" min="1" value="1" class="w-full px-4 py-2.5 border rounded font-mono">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-500 mb-2">Unit Cost (৳)</label>
                    <input required type="number" step="0.01" name="unit_cost" class="w-full px-4 py-2.5 border rounded font-mono">
                </div>
            </div>
            <input name="supplier_name" placeholder="Supplier name (optional)" class="w-full px-4 py-2.5 border rounded text-sm">
            <input name="supplier_phone" placeholder="Supplier phone (optional)" class="w-full px-4 py-2.5 border rounded text-sm">
            <div>
                <label class="block text-xs font-bold uppercase text-gray-500 mb-2">Payment Status</label>
                <select name="payment_status" class="w-full px-4 py-2.5 border rounded text-sm">
                    <option value="paid">Paid</option>
                    <option value="due">Due</option>
                    <option value="partial">Partial</option>
                </select>
            </div>
            <button class="w-full bg-ink text-white py-3 rounded text-sm font-bold uppercase">Receive Stock</button>
        </form>
    @else
        <p class="text-center py-8 text-gray-500 text-sm">Search a barcode to log received stock. New products must be added under Products &rarr; Add Product first.</p>
    @endif
</div>
@endsection
