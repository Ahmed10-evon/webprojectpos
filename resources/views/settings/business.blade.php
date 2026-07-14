@extends('layouts.app')
@section('title', 'Business Settings')
@section('heading', 'Business Settings')

@section('content')
<div class="max-w-md bg-white border rounded-lg p-6">
    <form method="POST" action="{{ route('settings.business.update') }}" class="space-y-4">
        @csrf @method('PUT')
        <div>
            <label class="block text-xs font-bold uppercase text-gray-500 mb-2">Business Name</label>
            <input required name="business_name" value="{{ $settings->business_name }}" class="w-full px-4 py-2.5 border rounded">
        </div>
        <div>
            <label class="block text-xs font-bold uppercase text-gray-500 mb-2">Address</label>
            <input name="address" value="{{ $settings->address }}" class="w-full px-4 py-2.5 border rounded">
        </div>
        <div>
            <label class="block text-xs font-bold uppercase text-gray-500 mb-2">Phone</label>
            <input name="phone" value="{{ $settings->phone }}" class="w-full px-4 py-2.5 border rounded">
        </div>
        <button class="w-full bg-ink text-white py-2.5 rounded text-xs font-bold uppercase">Save</button>
    </form>
</div>
@endsection
