@extends('layouts.app')
@section('title', 'Units')
@section('heading', 'Units')

@section('content')
<div class="max-w-lg bg-white border rounded-lg p-6">
    <form method="POST" action="{{ route('units.store') }}" class="flex gap-3 mb-6">
        @csrf
        <input required name="name" placeholder="Unit name (e.g. Piece)" class="flex-1 px-4 py-2.5 border rounded text-sm">
        <input name="short_code" placeholder="Code (e.g. pc)" class="w-28 px-4 py-2.5 border rounded text-sm">
        <button class="px-5 py-2.5 bg-ink text-white rounded text-xs font-bold uppercase">Add</button>
    </form>
    <div class="divide-y">
        @forelse($units as $u)
            <div class="py-3 flex items-center justify-between">
                <span class="text-sm font-semibold">{{ $u->name }} @if($u->short_code)<span class="text-gray-400 font-mono text-xs">({{ $u->short_code }})</span>@endif</span>
                <form method="POST" action="{{ route('units.destroy', $u) }}" onsubmit="return confirm('Remove {{ $u->name }}?')">
                    @csrf @method('DELETE')
                    <button class="text-red-500 text-xs font-bold uppercase">Remove</button>
                </form>
            </div>
        @empty
            <p class="text-center py-8 text-gray-500 text-sm">No units yet.</p>
        @endforelse
    </div>
</div>
@endsection
