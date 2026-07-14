@extends('layouts.app')
@section('title', 'Daily Sales Survey')
@section('heading', 'Daily Sales Survey')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="bg-white border rounded-lg p-6">
        <h3 class="font-bold text-sm mb-4">Manual Entry</h3>
        <form method="POST" action="{{ route('survey.store') }}" class="space-y-3 mb-4">
            @csrf
            <input required type="date" name="record_date" class="w-full px-4 py-2.5 border rounded text-sm">
            <input required type="number" step="0.01" name="amount" placeholder="Amount (৳)" class="w-full px-4 py-2.5 border rounded text-sm font-mono">
            <button class="w-full bg-ink text-white py-2.5 rounded text-xs font-bold uppercase">Save Entry</button>
        </form>
        <form method="POST" action="{{ route('survey.sync') }}">
            @csrf
            <button class="w-full border py-2.5 rounded text-xs font-bold uppercase">Sync From Real Sales</button>
        </form>
    </div>

    <div class="lg:col-span-2 bg-white border rounded-lg divide-y">
        <h3 class="font-bold text-sm px-6 pt-5 pb-3">Last 10 Days</h3>
        @forelse($records as $r)
            <div class="px-6 py-3 flex items-center justify-between gap-4">
                <p class="font-bold text-sm">{{ $r->record_date->format('Y-m-d, l') }}</p>
                <div class="flex items-center gap-3">
                    <p class="font-mono font-bold text-sm">৳{{ number_format($r->amount, 2) }}</p>
                    <form method="POST" action="{{ route('survey.destroy', $r) }}">
                        @csrf @method('DELETE')
                        <button class="text-red-500 text-xs font-bold uppercase">✕</button>
                    </form>
                </div>
            </div>
        @empty
            <p class="text-center py-12 text-gray-500 text-sm">No survey entries yet. Try "Sync From Real Sales".</p>
        @endforelse
    </div>
</div>
@endsection
