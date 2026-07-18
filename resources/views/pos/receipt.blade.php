@extends('layouts.app')
@section('title', 'Receipt')
@section('heading', 'Sale Complete')

@section('content')
<div class="max-w-sm mx-auto bg-white border rounded-lg p-6 font-mono text-sm">
    <div class="text-center mb-4 pb-4 border-b border-dashed">
        <p class="font-bold text-base">{{ $receipt['business']->business_name ?? 'CRAVE ABS' }}</p>
        @if($receipt['business']->address ?? null)<p class="text-xs text-gray-500">{{ $receipt['business']->address }}</p>@endif
        <p class="text-xs text-gray-500 mt-1">{{ \Carbon\Carbon::parse($receipt['sold_at'])->format('Y-m-d H:i:s') }}</p>
    </div>
    <div class="space-y-1 mb-4 pb-4 border-b border-dashed">
        @foreach($receipt['items'] as $item)
            <div class="flex justify-between">
                <span>{{ $item['name'] }} x{{ $item['qty'] }}</span>
                <span>৳{{ number_format($item['price'] * $item['qty'], 2) }}</span>
            </div>
        @endforeach
    </div>
    <div class="space-y-1 mb-4 pb-4 border-b border-dashed">
        <div class="flex justify-between"><span>Subtotal</span><span>৳{{ number_format($receipt['subtotal'], 2) }}</span></div>
        <div class="flex justify-between"><span>Discount</span><span>−৳{{ number_format($receipt['discount'], 2) }}</span></div>
        <div class="flex justify-between"><span>Tax</span><span>+৳{{ number_format($receipt['tax'], 2) }}</span></div>
        <div class="flex justify-between font-bold text-base"><span>TOTAL</span><span>৳{{ number_format($receipt['total'], 2) }}</span></div>
        <div class="flex justify-between text-xs text-gray-500 pt-1"><span>{{ strtoupper($receipt['payment_method']) }}</span><span>{{ $receipt['trx_id'] }}</span></div>
    </div>
    <p class="text-center text-xs">{{ $receipt['business']->receipt_footer_line1 ?? 'THANK YOU FOR SHOPPING!' }}</p>
    @if($receipt['business']->receipt_footer_line2 ?? null)<p class="text-center text-xs text-gray-500">{{ $receipt['business']->receipt_footer_line2 }}</p>@endif

    @php
        $qrText = ($receipt['business']->business_name ?? 'CRAVE ABS')
            .' | '.\Carbon\Carbon::parse($receipt['sold_at'])->format('Y-m-d H:i')
            .' | Total: BDT '.number_format($receipt['total'], 2)
            .' | '.strtoupper($receipt['payment_method'])
            .' | Ref: '.($receipt['receipt_ref'] ?? '');
    @endphp
    <div class="flex flex-col items-center mt-5 pt-5 border-t border-dashed">
        <img src="https://quickchart.io/qr?text={{ urlencode($qrText) }}&size=140&margin=1&ecLevel=Q"
             alt="Receipt QR code" width="140" height="140">
        <p class="text-[10px] text-gray-400 mt-2">Scan to verify this receipt · {{ $receipt['receipt_ref'] ?? '' }}</p>
    </div>

    <div class="flex gap-2 mt-6 print:hidden">
        <button onclick="window.print()" class="flex-1 py-2.5 bg-ink text-white rounded text-xs font-bold uppercase">Print</button>
        <a href="{{ route('pos.index') }}" class="flex-1 text-center py-2.5 border rounded text-xs font-bold uppercase">New Sale</a>
    </div>
</div>
@endsection
