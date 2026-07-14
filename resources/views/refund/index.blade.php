@extends('layouts.app')
@section('title', 'Refund')
@section('heading', 'Refund & Returns')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white border rounded-lg p-6">
        <form method="GET" class="flex gap-3 mb-6">
            <input name="barcode" value="{{ $barcode }}" placeholder="Scan or type product barcode..." class="flex-1 px-4 py-2.5 border rounded text-sm font-mono" autofocus>
            <button class="px-5 py-2.5 bg-ink text-white rounded text-xs font-bold uppercase">Find Sales</button>
        </form>

        <div class="divide-y">
            @forelse($sales as $sale)
                <div class="py-4 flex items-center justify-between gap-4">
                    <div>
                        <p class="font-bold text-sm">{{ $sale->product->name }}</p>
                        <p class="text-xs text-gray-500 font-mono">{{ $sale->sold_at->format('Y-m-d H:i') }} · ৳{{ number_format($sale->amount_paid, 2) }}</p>
                    </div>
                    <form method="POST" action="{{ route('refund.process', $sale) }}" onsubmit="return confirm('Refund this sale and restock 1 unit?')">
                        @csrf
                        <button class="px-4 py-2 bg-red-600 text-white rounded text-xs font-bold uppercase">Refund</button>
                    </form>
                </div>
            @empty
                <p class="text-center py-8 text-gray-500 text-sm">{{ $barcode ? 'No active sales found for that barcode.' : 'Search a barcode to find refundable sales.' }}</p>
            @endforelse
        </div>
    </div>

    <div class="bg-white border rounded-lg divide-y">
        <h3 class="font-bold text-sm px-6 pt-5 pb-3">Recent Refunds</h3>
        @forelse($recentReturns as $r)
            <div class="px-6 py-3">
                <p class="font-bold text-sm line-through text-gray-400">{{ $r->product?->name }}</p>
                <p class="text-xs text-gray-500">{{ $r->sold_at->format('Y-m-d H:i') }} · ৳{{ number_format($r->amount_paid, 2) }}</p>
            </div>
        @empty
            <p class="text-center py-12 text-gray-500 text-sm">No refunds yet.</p>
        @endforelse
    </div>
</div>
@endsection
