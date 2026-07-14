@extends('layouts.app')
@section('title', 'Reports')
@section('heading', 'Reports')

@section('content')
<div class="space-y-6">
    <form method="GET" class="bg-white border rounded-lg p-5 flex flex-wrap items-end gap-4">
        <div>
            <label class="block text-[10px] font-bold uppercase text-gray-500 mb-1">Start Date</label>
            <input type="date" name="start_date" value="{{ $startDate }}" class="px-3 py-2 border rounded text-sm">
        </div>
        <div>
            <label class="block text-[10px] font-bold uppercase text-gray-500 mb-1">End Date</label>
            <input type="date" name="end_date" value="{{ $endDate }}" class="px-3 py-2 border rounded text-sm">
        </div>
        <button class="px-5 py-2.5 bg-ink text-white rounded text-xs font-bold uppercase">Filter</button>
        <a href="{{ route('reports.export', request()->query()) }}" class="px-5 py-2.5 border rounded text-xs font-bold uppercase">Export CSV</a>
    </form>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="bg-ink text-white p-6 rounded-lg">
            <p class="text-xs font-bold uppercase text-gray-400 mb-2">Total Revenue</p>
            <p class="font-mono text-2xl font-bold">৳{{ number_format($totalRevenue, 2) }}</p>
        </div>
        <div class="bg-white border p-6 rounded-lg">
            <p class="text-xs font-bold uppercase text-gray-500 mb-2">Total Costs ({{ $costsCount }})</p>
            <p class="font-mono text-2xl font-bold">৳{{ number_format($totalCosts, 2) }}</p>
        </div>
        <div class="{{ $netProfit >= 0 ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200' }} border p-6 rounded-lg">
            <p class="text-xs font-bold uppercase {{ $netProfit >= 0 ? 'text-green-700' : 'text-red-600' }} mb-2">Net Profit</p>
            <p class="font-mono text-2xl font-bold {{ $netProfit >= 0 ? 'text-green-700' : 'text-red-600' }}">৳{{ number_format($netProfit, 2) }}</p>
        </div>
    </div>

    <div class="bg-white border rounded-lg p-6">
        <h3 class="font-bold text-sm mb-4">Revenue by Payment Method</h3>
        <div class="grid grid-cols-2 sm:grid-cols-6 gap-4">
            @foreach($methods as $method => $amount)
                <div>
                    <p class="text-[10px] font-bold uppercase text-gray-500">{{ $method }}</p>
                    <p class="font-mono font-bold text-sm">৳{{ number_format($amount, 2) }}</p>
                </div>
            @endforeach
        </div>
    </div>

    <div class="bg-white border rounded-lg divide-y">
        <h3 class="font-bold text-sm px-6 pt-5 pb-3">Ledger</h3>
        @forelse($sales->take(100) as $sale)
            <div class="px-6 py-3 flex items-center justify-between gap-4">
                <div>
                    <p class="font-bold text-sm {{ $sale->status === 'refunded' ? 'line-through text-gray-400' : '' }}">{{ $sale->product?->name }}</p>
                    <p class="text-xs text-gray-500 font-mono">{{ $sale->sold_at->format('Y-m-d H:i') }}</p>
                </div>
                <p class="font-mono font-bold text-sm {{ $sale->status === 'refunded' ? 'line-through text-gray-400' : '' }}">৳{{ number_format($sale->amount_paid, 2) }}</p>
            </div>
        @empty
            <p class="text-center py-12 text-gray-500 text-sm">No transactions in this range.</p>
        @endforelse
    </div>
</div>
@endsection
