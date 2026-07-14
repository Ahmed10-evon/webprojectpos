@extends('layouts.app')
@section('title', 'Invoice Settings')
@section('heading', 'Invoice Settings')

@section('content')
<div class="max-w-md bg-white border rounded-lg p-6">
    <form method="POST" action="{{ route('settings.invoice.update') }}" class="space-y-4">
        @csrf @method('PUT')
        <div>
            <label class="block text-xs font-bold uppercase text-gray-500 mb-2">Receipt Footer Line 1</label>
            <input required name="receipt_footer_line1" value="{{ $settings->receipt_footer_line1 }}" class="w-full px-4 py-2.5 border rounded">
        </div>
        <div>
            <label class="block text-xs font-bold uppercase text-gray-500 mb-2">Receipt Footer Line 2</label>
            <input name="receipt_footer_line2" value="{{ $settings->receipt_footer_line2 }}" class="w-full px-4 py-2.5 border rounded">
        </div>
        <button class="w-full bg-ink text-white py-2.5 rounded text-xs font-bold uppercase">Save</button>
    </form>
</div>
@endsection
