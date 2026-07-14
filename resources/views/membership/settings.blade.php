@extends('layouts.app')
@section('title', 'Membership Settings')
@section('heading', 'Membership Settings')

@section('content')
<div class="max-w-md bg-white border rounded-lg p-6">
    <form method="POST" action="{{ route('membership.settings.update') }}" class="space-y-4">
        @csrf @method('PUT')
        <div>
            <label class="block text-xs font-bold uppercase text-gray-500 mb-2">Member Discount (%)</label>
            <input required type="number" step="0.01" name="discount_percent" value="{{ $settings->discount_percent }}" class="w-full px-4 py-2.5 border rounded font-mono">
        </div>
        <button class="w-full bg-ink text-white py-2.5 rounded text-xs font-bold uppercase">Save</button>
    </form>
</div>
@endsection
