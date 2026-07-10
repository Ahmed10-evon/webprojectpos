<?php

namespace App\Http\Controllers\Purchases;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Http\Request;

class AddPurchaseController extends Controller
{
    public function index(Request $request)
    {
        $barcode = $request->query('barcode', '');
        $match = $barcode ? Product::where('barcode', $barcode)->first() : null;

        if ($barcode && ! $match) {
            $request->session()->flash('error', 'No product with that barcode. Add it under Products first.');
        }

        return view('purchases.add', ['match' => $match, 'barcode' => $barcode]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'unit_cost' => ['required', 'numeric', 'min:0'],
            'supplier_name' => ['nullable', 'string'],
            'supplier_phone' => ['nullable', 'string'],
            'payment_status' => ['required', 'in:paid,due,partial'],
        ]);

        $product = Product::findOrFail($data['product_id']);

        Purchase::create([
            'product_id' => $product->id,
            'item_name' => $product->name,
            'barcode' => $product->barcode,
            'quantity' => $data['quantity'],
            'unit_cost' => $data['unit_cost'],
            'total_cost' => $data['quantity'] * $data['unit_cost'],
            'supplier_name' => $data['supplier_name'] ?? null,
            'supplier_phone' => $data['supplier_phone'] ?? null,
            'payment_status' => $data['payment_status'],
            'purchased_at' => now(),
        ]);

        $newQuantity = $product->quantity + $data['quantity'];
        $product->update(['quantity' => $newQuantity, 'status' => 'available']);

        return redirect()->route('purchases.add.index')
            ->with('success', "Stock updated — {$product->name} now has {$newQuantity} on hand.");
    }
}
