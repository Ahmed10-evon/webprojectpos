@extends('layouts.app')
@section('title', 'Sales Orders')
@section('heading', 'Sales Orders')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="bg-white border rounded-lg p-6">
        <h3 class="font-bold text-sm mb-4">Log New Order</h3>
        <form method="POST" action="{{ route('sales.orders.store') }}" class="space-y-3">
            @csrf
            <input required name="customer_name" placeholder="Customer name" class="w-full px-4 py-2.5 border rounded text-sm">
            <input name="customer_phone" placeholder="Phone (optional)" class="w-full px-4 py-2.5 border rounded text-sm">
            <input required name="item_description" placeholder="Item description" class="w-full px-4 py-2.5 border rounded text-sm">
            <div class="grid grid-cols-2 gap-3">
                <input required type="number" name="quantity" value="1" min="1" placeholder="Qty" class="px-4 py-2.5 border rounded text-sm">
                <input type="number" step="0.01" name="unit_price" placeholder="Unit Price" class="px-4 py-2.5 border rounded text-sm">
            </div>
            <input type="date" name="expected_date" class="w-full px-4 py-2.5 border rounded text-sm">
            <textarea name="notes" placeholder="Notes" class="w-full px-4 py-2.5 border rounded text-sm" rows="2"></textarea>
            <button class="w-full bg-ink text-white py-2.5 rounded text-xs font-bold uppercase">Log Order</button>
        </form>
    </div>

    <div class="lg:col-span-2 bg-white border rounded-lg divide-y">
        @forelse($orders as $order)
            <div class="px-6 py-4 flex items-center justify-between gap-4">
                <div>
                    <p class="font-bold text-sm">{{ $order->item_description }} <span class="text-gray-400 font-normal">x{{ $order->quantity }}</span></p>
                    <p class="text-xs text-gray-500">{{ $order->customer_name }} @if($order->customer_phone) · {{ $order->customer_phone }} @endif</p>
                </div>
                <form method="POST" action="{{ route('sales.orders.status', $order) }}">
                    @csrf @method('PATCH')
                    <select name="status" onchange="this.form.submit()" class="text-xs font-bold uppercase px-2 py-1.5 border rounded
                        {{ $order->status === 'fulfilled' ? 'bg-green-50 text-green-700' : ($order->status === 'cancelled' ? 'bg-red-50 text-red-600' : 'bg-yellow-50 text-yellow-700') }}">
                        <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="fulfilled" {{ $order->status === 'fulfilled' ? 'selected' : '' }}>Fulfilled</option>
                        <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </form>
            </div>
        @empty
            <p class="text-center py-12 text-gray-500 text-sm">No sales orders yet.</p>
        @endforelse
    </div>
</div>
@endsection
