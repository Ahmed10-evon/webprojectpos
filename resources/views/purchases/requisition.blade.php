@extends('layouts.app')
@section('title', 'Requisition')
@section('heading', 'Purchase Requisition')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="bg-white border rounded-lg p-6">
        <h3 class="font-bold text-sm mb-4">New Requisition</h3>
        <form method="POST" action="{{ route('purchases.requisition.store') }}" class="space-y-3">
            @csrf
            <input required name="item_description" placeholder="Item needed" class="w-full px-4 py-2.5 border rounded text-sm">
            <input required type="number" name="quantity_needed" min="1" placeholder="Quantity needed" class="w-full px-4 py-2.5 border rounded text-sm">
            <input name="preferred_supplier" placeholder="Preferred supplier (optional)" class="w-full px-4 py-2.5 border rounded text-sm">
            <textarea name="notes" placeholder="Notes" class="w-full px-4 py-2.5 border rounded text-sm" rows="2"></textarea>
            <button class="w-full bg-ink text-white py-2.5 rounded text-xs font-bold uppercase">Log Requisition</button>
        </form>
    </div>
    <div class="lg:col-span-2 bg-white border rounded-lg divide-y">
        @forelse($requisitions as $r)
            <div class="px-6 py-4 flex items-center justify-between gap-4">
                <div>
                    <p class="font-bold text-sm">{{ $r->item_description }} <span class="text-gray-400 font-normal">x{{ $r->quantity_needed }}</span></p>
                    @if($r->preferred_supplier)<p class="text-xs text-gray-500">Preferred: {{ $r->preferred_supplier }}</p>@endif
                </div>
                <form method="POST" action="{{ route('purchases.requisition.status', $r) }}">
                    @csrf @method('PATCH')
                    <select name="status" onchange="this.form.submit()" class="text-xs font-bold uppercase px-2 py-1.5 border rounded
                        {{ $r->status === 'fulfilled' ? 'bg-green-50 text-green-700' : ($r->status === 'ordered' ? 'bg-blue-50 text-blue-700' : 'bg-yellow-50 text-yellow-700') }}">
                        <option value="pending" {{ $r->status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="ordered" {{ $r->status === 'ordered' ? 'selected' : '' }}>Ordered</option>
                        <option value="fulfilled" {{ $r->status === 'fulfilled' ? 'selected' : '' }}>Fulfilled</option>
                    </select>
                </form>
            </div>
        @empty
            <p class="text-center py-12 text-gray-500 text-sm">No requisitions logged.</p>
        @endforelse
    </div>
</div>
@endsection
