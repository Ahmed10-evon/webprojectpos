@extends('layouts.app')
@section('title', 'Daily Cost')
@section('heading', 'Daily Cost')

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="bg-white border p-6 rounded-lg">
            <p class="text-xs font-bold uppercase text-gray-500 mb-2">Today ({{ $todaysCount }})</p>
            <p class="font-mono text-2xl font-bold">৳{{ number_format($todaysTotal, 2) }}</p>
        </div>
        <div class="bg-white border p-6 rounded-lg">
            <p class="text-xs font-bold uppercase text-gray-500 mb-2">This Month ({{ $monthCount }})</p>
            <p class="font-mono text-2xl font-bold">৳{{ number_format($monthTotal, 2) }}</p>
        </div>
        <div class="bg-ink text-white p-6 rounded-lg">
            <p class="text-xs font-bold uppercase text-gray-400 mb-2">All Time</p>
            <p class="font-mono text-2xl font-bold">৳{{ number_format($allTimeTotal, 2) }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white border rounded-lg p-6">
            <h3 class="font-bold text-sm mb-4">Log a Cost</h3>
            <form method="POST" action="{{ route('daily-cost.store') }}" class="space-y-3">
                @csrf
                <input required type="date" name="cost_date" value="{{ now()->toDateString() }}" class="w-full px-4 py-2.5 border rounded text-sm">
                <input required type="number" step="0.01" name="amount" placeholder="Amount (৳)" class="w-full px-4 py-2.5 border rounded text-sm font-mono">
                <input required name="note" placeholder="What was this for?" class="w-full px-4 py-2.5 border rounded text-sm">
                <button class="w-full bg-ink text-white py-2.5 rounded text-xs font-bold uppercase">Record Cost</button>
            </form>
        </div>

        <div class="lg:col-span-2 bg-white border rounded-lg divide-y">
            @forelse($costs as $c)
                <div class="px-6 py-3 flex items-center justify-between gap-4">
                    <div>
                        <p class="font-bold text-sm">{{ $c->note }}</p>
                        <p class="text-xs text-gray-500 font-mono">{{ $c->cost_date->format('Y-m-d') }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <p class="font-mono font-bold text-sm">৳{{ number_format($c->amount, 2) }}</p>
                        <form method="POST" action="{{ route('daily-cost.destroy', $c) }}">
                            @csrf @method('DELETE')
                            <button class="text-red-500 text-xs font-bold uppercase">✕</button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="text-center py-12 text-gray-500 text-sm">No costs logged yet.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
