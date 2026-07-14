@extends('layouts.app')
@section('title', 'Purchase History')
@section('heading', 'List Purchases')

@section('content')
<div class="bg-white border rounded-lg divide-y">
    @forelse($purchases as $p)
        <div class="px-6 py-4 flex items-center justify-between gap-4">
            <div>
                <p class="font-bold text-sm">{{ $p->item_name }} <span class="text-gray-400 font-normal">x{{ $p->quantity }}</span></p>
                <p class="text-xs text-gray-500 font-mono">{{ $p->purchased_at->format('Y-m-d H:i') }} {{ $p->supplier_name ? '· '.$p->supplier_name : '' }}</p>
            </div>
            <div class="text-right">
                <p class="font-mono font-bold text-sm">৳{{ number_format($p->total_cost, 2) }}</p>
                <span class="text-[10px] px-2 py-0.5 font-bold uppercase rounded
                    {{ $p->payment_status === 'paid' ? 'bg-green-100 text-green-700' : ($p->payment_status === 'due' ? 'bg-red-100 text-red-600' : 'bg-yellow-100 text-yellow-700') }}">{{ $p->payment_status }}</span>
            </div>
        </div>
    @empty
        <p class="text-center py-12 text-gray-500 text-sm">No purchases logged yet.</p>
    @endforelse
</div>
<div class="mt-4">{{ $purchases->links() }}</div>
@endsection
