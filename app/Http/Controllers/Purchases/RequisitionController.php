<?php

namespace App\Http\Controllers\Purchases;

use App\Http\Controllers\Controller;
use App\Models\PurchaseRequisition;
use Illuminate\Http\Request;

class RequisitionController extends Controller
{
    public function index()
    {
        return view('purchases.requisition', [
            'requisitions' => PurchaseRequisition::latest()->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'item_description' => ['required', 'string'],
            'quantity_needed' => ['required', 'integer', 'min:1'],
            'preferred_supplier' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ]);
        $data['status'] = 'pending';

        PurchaseRequisition::create($data);

        return back()->with('success', 'Requisition logged.');
    }

    public function updateStatus(Request $request, PurchaseRequisition $requisition)
    {
        $data = $request->validate(['status' => ['required', 'in:pending,ordered,fulfilled']]);
        $requisition->update($data);

        return back();
    }
}
