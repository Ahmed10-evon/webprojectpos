@extends('layouts.app')
@section('title', 'All Sales')
@section('heading', 'All Sales')

@section('content')
<div class="bg-white border rounded-lg">
    <form method="GET" class="p-6 pb-4">
        <input type="text" name="q" value="{{ $search }}" placeholder="Search by item or barcode..." class="w-full px-4 py-2.5 border rounded text-sm">
    </form>
    <div class="divide-y">
        @forelse($sales as $sale)
            <div class="px-6 py-4 flex items-center justify-between gap-4">
                <div>
                    <p class="font-bold text-sm {{ $sale->status === 'refunded' ? 'line-through text-gray-400' : '' }}">{{ $sale->product?->name ?? 'Deleted Item' }}</p>
                    <p class="text-xs text-gray-500 font-mono">{{ $sale->sold_at->format('Y-m-d H:i') }} · {{ strtoupper($sale->transaction_id === 'BANK/CARD-SALE' ? 'bank/card' : $sale->payment_method) }}</p>
                </div>
                <div class="text-right">
                    <p class="font-mono font-bold text-sm {{ $sale->status === 'refunded' ? 'line-through text-gray-400' : '' }}">৳{{ number_format($sale->amount_paid, 2) }}</p>
                    <span class="text-[10px] px-2 py-0.5 font-bold uppercase rounded {{ $sale->status === 'refunded' ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-700' }}">{{ $sale->status }}</span>
                </div>
            </div>
        @empty
            <p class="text-center py-12 text-gray-500 text-sm">No sales recorded yet.</p>
        @endforelse
    </div>
    <div class="p-6">{{ $sales->links() }}</div>
</div>
@endsection
