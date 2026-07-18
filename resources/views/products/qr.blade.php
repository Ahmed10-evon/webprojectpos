@extends('layouts.app')
@section('title', 'Product QR Code')
@section('heading', 'Product QR Code')

@section('content')
<div class="max-w-sm mx-auto">
    <div class="bg-white border rounded-lg p-6 text-center">
        <p class="font-bold text-sm">{{ $product->name }}</p>
        <p class="text-xs text-gray-500 font-mono mb-4">{{ $product->barcode }}</p>

        <img src="https://quickchart.io/qr?text={{ urlencode($product->barcode) }}&size=220&margin=1&ecLevel=Q"
             alt="QR code for {{ $product->barcode }}" width="220" height="220" class="mx-auto">

        <p class="font-mono text-sm font-bold mt-4">৳{{ number_format($product->price, 2) }}</p>

        <div class="flex gap-2 mt-6 print:hidden">
            <button onclick="window.print()" class="flex-1 py-2.5 bg-ink text-white rounded text-xs font-bold uppercase">Print Label</button>
            <a href="{{ route('products.index') }}" class="flex-1 text-center py-2.5 border rounded text-xs font-bold uppercase">Back</a>
        </div>
    </div>
</div>
@endsection
