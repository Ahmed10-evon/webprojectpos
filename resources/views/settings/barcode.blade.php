@extends('layouts.app')
@section('title', 'Barcode Settings')
@section('heading', 'Barcode Settings')

@section('content')
<div class="max-w-md bg-white border rounded-lg p-6">
    <form method="POST" action="{{ route('settings.barcode.update') }}" class="space-y-4">
        @csrf @method('PUT')
        <div>
            <label class="block text-xs font-bold uppercase text-gray-500 mb-2">Barcode Prefix</label>
            <input required name="barcode_prefix" maxlength="10" value="{{ $settings->barcode_prefix }}" class="w-full px-4 py-2.5 border rounded font-mono uppercase">
        </div>
        <button class="w-full bg-ink text-white py-2.5 rounded text-xs font-bold uppercase">Save</button>
    </form>
</div>
@endsection
