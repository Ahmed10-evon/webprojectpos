<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\SalesOrder;
use Illuminate\Http\Request;

class SalesOrderController extends Controller
{
    public function index()
    {
        return view('sales.orders', ['orders' => SalesOrder::latest()->get()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_name' => ['required', 'string'],
            'customer_phone' => ['nullable', 'string'],
            'item_description' => ['required', 'string'],
            'quantity' => ['required', 'integer', 'min:1'],
            'unit_price' => ['nullable', 'numeric', 'min:0'],
            'expected_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ]);
        $data['status'] = 'pending';

        SalesOrder::create($data);

        return back()->with('success', 'Sales order logged.');
    }

    public function updateStatus(Request $request, SalesOrder $salesOrder)
    {
        $data = $request->validate(['status' => ['required', 'in:pending,fulfilled,cancelled']]);
        $salesOrder->update($data);

        return back();
    }
}
