@extends('layouts.app')
@section('title', 'Brands')
@section('heading', 'Brands')

@section('content')
<div class="max-w-lg bg-white border rounded-lg p-6">
    <form method="POST" action="{{ route('brands.store') }}" class="flex gap-3 mb-6">
        @csrf
        <input required name="name" placeholder="New brand name" class="flex-1 px-4 py-2.5 border rounded text-sm">
        <button class="px-5 py-2.5 bg-ink text-white rounded text-xs font-bold uppercase">Add</button>
    </form>
    <div class="divide-y">
        @forelse($brands as $b)
            <div class="py-3 flex items-center justify-between">
                <span class="text-sm font-semibold">{{ $b->name }}</span>
                <form method="POST" action="{{ route('brands.destroy', $b) }}" onsubmit="return confirm('Remove {{ $b->name }}?')">
                    @csrf @method('DELETE')
                    <button class="text-red-500 text-xs font-bold uppercase">Remove</button>
                </form>
            </div>
        @empty
            <p class="text-center py-8 text-gray-500 text-sm">No brands yet.</p>
        @endforelse
    </div>
</div>
@endsection
