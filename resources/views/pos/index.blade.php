@extends('layouts.app')
@section('title', 'POS Terminal')
@section('heading', 'POS Terminal')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-4">
        <form method="POST" action="{{ route('pos.scan') }}" class="bg-white border rounded-lg p-5">
            @csrf
            <label class="block text-xs font-bold uppercase text-gray-500 mb-2">Scan Barcode</label>
            <div class="flex gap-3">
                <input name="barcode" autofocus class="flex-1 px-4 py-3 border rounded font-mono text-lg" placeholder="Scan or type barcode, then Enter">
                <button class="px-6 bg-ink text-white rounded font-bold text-sm uppercase">Add</button>
            </div>
        </form>

        <div class="bg-white border rounded-lg">
            <h3 class="font-bold text-sm px-6 pt-5 pb-3">Cart ({{ count($cart) }})</h3>
            <div class="divide-y">
                @forelse($cart as $item)
                    <div class="px-6 py-4 flex items-center justify-between gap-4">
                        <div>
                            <p class="font-bold text-sm">{{ $item['name'] }}</p>
                            <p class="text-xs text-gray-500 font-mono">{{ $item['barcode'] }} · ৳{{ number_format($item['price'], 2) }} each</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <form method="POST" action="{{ route('pos.qty', $item['id']) }}" class="flex items-center gap-2">
                                @csrf
                                <button name="increment" value="0" class="w-7 h-7 border rounded font-bold">−</button>
                                <span class="w-6 text-center font-mono font-bold">{{ $item['qty'] }}</span>
                                <button name="increment" value="1" class="w-7 h-7 border rounded font-bold">+</button>
                            </form>
                            <p class="font-mono font-bold text-sm w-20 text-right">৳{{ number_format($item['price'] * $item['qty'], 2) }}</p>
                            <form method="POST" action="{{ route('pos.remove', $item['id']) }}">
                                @csrf @method('DELETE')
                                <button class="text-red-500 text-xs font-bold uppercase">✕</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-center py-12 text-gray-500 text-sm">Cart is empty — scan an item to begin.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="bg-ink text-white rounded-lg p-6 h-fit sticky top-8">
        <h3 class="font-bold text-sm mb-5 uppercase tracking-wider text-gray-400">Checkout</h3>

        <form method="POST" action="{{ route('pos.discountTax') }}" class="space-y-3 mb-5 pb-5 border-b border-white/10">
            @csrf
            <div>
                <label class="block text-[10px] font-bold uppercase text-gray-400 mb-1">Discount (৳)</label>
                <input type="number" step="0.01" name="discount" value="{{ session('pos.discount', 0) }}" class="w-full px-3 py-2 rounded bg-white/5 border border-white/10 text-sm font-mono">
            </div>
            <div>
                <label class="block text-[10px] font-bold uppercase text-gray-400 mb-1">Tax Rate</label>
                <select name="tax_rate_id" class="w-full px-3 py-2 rounded bg-white/5 border border-white/10 text-sm">
                    <option value="">No tax</option>
                    @foreach($taxRates as $rate)
                        <option value="{{ $rate->id }}" {{ session('pos.tax_rate_id') == $rate->id ? 'selected' : '' }}>{{ $rate->name }} ({{ $rate->rate_percent }}%)</option>
                    @endforeach
                </select>
            </div>
            <button class="w-full py-2 border border-white/20 rounded text-xs font-bold uppercase">Apply</button>
        </form>

        <div class="space-y-2 text-sm font-mono mb-5">
            <div class="flex justify-between"><span class="text-gray-400">Subtotal</span><span>৳{{ number_format($totals['subtotal'], 2) }}</span></div>
            <div class="flex justify-between"><span class="text-gray-400">Discount</span><span>−৳{{ number_format($totals['discount'], 2) }}</span></div>
            <div class="flex justify-between"><span class="text-gray-400">Tax</span><span>+৳{{ number_format($totals['tax'], 2) }}</span></div>
            <div class="flex justify-between text-lg font-bold pt-2 border-t border-white/10"><span>Total</span><span>৳{{ number_format($totals['total'], 2) }}</span></div>
        </div>

        <form method="POST" action="{{ route('pos.checkout') }}" class="space-y-3">
            @csrf
            <select name="payment_method" required class="w-full px-3 py-2.5 rounded bg-white/5 border border-white/10 text-sm">
                @foreach($paymentMethods as $method)
                    <option value="{{ $method }}">{{ strtoupper($method) }}</option>
                @endforeach
            </select>
            <input name="trx_id" placeholder="Transaction ID (mobile banking)" class="w-full px-3 py-2.5 rounded bg-white/5 border border-white/10 text-sm font-mono">
            <button class="w-full py-3 bg-brass text-white rounded font-bold text-sm uppercase" {{ count($cart) === 0 ? 'disabled' : '' }}>Complete Sale</button>
        </form>
    </div>
</div>
@endsection
