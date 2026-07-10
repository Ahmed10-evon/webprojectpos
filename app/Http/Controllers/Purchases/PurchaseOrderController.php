<?php

namespace App\Http\Controllers\Purchases;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        return view('purchases.orders', [
            'orders' => PurchaseOrder::with('items')->latest()->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'supplier_name' => ['required', 'string'],
            'supplier_phone' => ['nullable', 'string'],
            'expected_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.description' => ['required', 'string'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.unit_cost' => ['required', 'numeric', 'min:0'],
        ]);

        $order = PurchaseOrder::create([
            'supplier_name' => $data['supplier_name'],
            'supplier_phone' => $data['supplier_phone'] ?? null,
            'expected_date' => $data['expected_date'] ?? null,
            'notes' => $data['notes'] ?? null,
            'status' => 'ordered',
        ]);

        foreach ($data['items'] as $item) {
            $order->items()->create([
                'item_description' => $item['description'],
                'quantity_ordered' => $item['quantity'],
                'unit_cost' => $item['unit_cost'],
            ]);
        }

        return redirect()->route('purchases.orders.index')->with('success', 'Purchase order created.');
    }

    public function updateStatus(Request $request, PurchaseOrder $order)
    {
        $data = $request->validate(['status' => ['required', 'in:ordered,received,cancelled']]);
        $order->update($data);

        return back();
    }
}
