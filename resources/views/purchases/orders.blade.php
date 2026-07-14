@extends('layouts.app')
@section('title', 'Purchase Orders')
@section('heading', 'Purchase Orders')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white border rounded-lg p-6">
        <h3 class="font-bold text-sm mb-4">New Purchase Order</h3>
        <form method="POST" action="{{ route('purchases.orders.store') }}" class="space-y-3">
            @csrf
            <input required name="supplier_name" placeholder="Supplier name" class="w-full px-4 py-2.5 border rounded text-sm">
            <input name="supplier_phone" placeholder="Supplier phone" class="w-full px-4 py-2.5 border rounded text-sm">
            <input type="date" name="expected_date" class="w-full px-4 py-2.5 border rounded text-sm">

            <div id="po-items" class="space-y-2">
                <div class="grid grid-cols-6 gap-2">
                    <input required name="items[0][description]" placeholder="Item" class="col-span-3 px-3 py-2 border rounded text-sm">
                    <input required type="number" name="items[0][quantity]" placeholder="Qty" class="px-3 py-2 border rounded text-sm">
                    <input required type="number" step="0.01" name="items[0][unit_cost]" placeholder="Cost" class="col-span-2 px-3 py-2 border rounded text-sm">
                </div>
            </div>
            <button type="button" onclick="addPoRow()" class="text-xs font-bold uppercase text-brass">+ Add Line Item</button>

            <textarea name="notes" placeholder="Notes" class="w-full px-4 py-2.5 border rounded text-sm" rows="2"></textarea>
            <button class="w-full bg-ink text-white py-2.5 rounded text-xs font-bold uppercase">Create Order</button>
        </form>
    </div>

    <div class="bg-white border rounded-lg divide-y">
        @forelse($orders as $order)
            <div class="px-6 py-4">
                <div class="flex items-center justify-between mb-2">
                    <p class="font-bold text-sm">{{ $order->supplier_name }}</p>
                    <form method="POST" action="{{ route('purchases.orders.status', $order) }}">
                        @csrf @method('PATCH')
                        <select name="status" onchange="this.form.submit()" class="text-xs font-bold uppercase px-2 py-1 border rounded
                            {{ $order->status === 'received' ? 'bg-green-50 text-green-700' : ($order->status === 'cancelled' ? 'bg-red-50 text-red-600' : 'bg-blue-50 text-blue-700') }}">
                            <option value="ordered" {{ $order->status === 'ordered' ? 'selected' : '' }}>Ordered</option>
                            <option value="received" {{ $order->status === 'received' ? 'selected' : '' }}>Received</option>
                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </form>
                </div>
                <ul class="text-xs text-gray-500 space-y-0.5">
                    @foreach($order->items as $item)
                        <li>{{ $item->item_description }} — {{ $item->quantity_ordered }} x ৳{{ number_format($item->unit_cost, 2) }}</li>
                    @endforeach
                </ul>
            </div>
        @empty
            <p class="text-center py-12 text-gray-500 text-sm">No purchase orders yet.</p>
        @endforelse
    </div>
</div>

<script>
let poRowIndex = 1;
function addPoRow() {
    const wrap = document.getElementById('po-items');
    const row = document.createElement('div');
    row.className = 'grid grid-cols-6 gap-2';
    row.innerHTML = `
        <input required name="items[${poRowIndex}][description]" placeholder="Item" class="col-span-3 px-3 py-2 border rounded text-sm">
        <input required type="number" name="items[${poRowIndex}][quantity]" placeholder="Qty" class="px-3 py-2 border rounded text-sm">
        <input required type="number" step="0.01" name="items[${poRowIndex}][unit_cost]" placeholder="Cost" class="col-span-2 px-3 py-2 border rounded text-sm">`;
    wrap.appendChild(row);
    poRowIndex++;
}
</script>
@endsection
