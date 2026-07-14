@extends('layouts.app')
@section('title', 'Tax Rates')
@section('heading', 'Tax Rates')

@section('content')
<div class="max-w-lg bg-white border rounded-lg p-6">
    <form method="POST" action="{{ route('settings.tax.store') }}" class="flex gap-3 mb-6">
        @csrf
        <input required name="name" placeholder="Tax name (e.g. VAT)" class="flex-1 px-4 py-2.5 border rounded text-sm">
        <input required type="number" step="0.01" name="rate_percent" placeholder="%" class="w-24 px-4 py-2.5 border rounded text-sm font-mono">
        <button class="px-5 py-2.5 bg-ink text-white rounded text-xs font-bold uppercase">Add</button>
    </form>
    <div class="divide-y">
        @forelse($taxRates as $t)
            <div class="py-3 flex items-center justify-between">
                <span class="text-sm font-semibold">{{ $t->name }} — {{ $t->rate_percent }}%</span>
                <form method="POST" action="{{ route('settings.tax.destroy', $t) }}">
                    @csrf @method('DELETE')
                    <button class="text-red-500 text-xs font-bold uppercase">Remove</button>
                </form>
            </div>
        @empty
            <p class="text-center py-8 text-gray-500 text-sm">No tax rates configured.</p>
        @endforelse
    </div>
</div>
@endsection
