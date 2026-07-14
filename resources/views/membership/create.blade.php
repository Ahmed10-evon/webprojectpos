@extends('layouts.app')
@section('title', 'Add Member')
@section('heading', 'Enroll Member')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white border rounded-lg p-6">
        <p class="text-sm text-gray-500 mb-4">New members get a 1-year membership with a <span class="font-bold text-brass">{{ $settings->discount_percent }}%</span> discount.</p>
        <form method="POST" action="{{ route('membership.store') }}" class="space-y-3">
            @csrf
            <input required name="phone" placeholder="Customer phone number" class="w-full px-4 py-2.5 border rounded text-sm font-mono">
            <input name="note" placeholder="Note (optional)" class="w-full px-4 py-2.5 border rounded text-sm">
            <button class="w-full bg-ink text-white py-2.5 rounded text-xs font-bold uppercase">Enroll Member</button>
        </form>
    </div>
    <div class="bg-white border rounded-lg divide-y">
        <h3 class="font-bold text-sm px-6 pt-5 pb-3">Recently Enrolled</h3>
        @forelse($recent as $m)
            <div class="px-6 py-3">
                <p class="font-bold text-sm font-mono">{{ $m->phone }}</p>
                <p class="text-xs text-gray-500">Since {{ $m->start_date->format('Y-m-d') }}</p>
            </div>
        @empty
            <p class="text-center py-12 text-gray-500 text-sm">No members yet.</p>
        @endforelse
    </div>
</div>
@endsection
