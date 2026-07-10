<?php

namespace App\Http\Controllers\Purchases;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\PurchaseReturn;
use Illuminate\Http\Request;

class PurchaseReturnController extends Controller
{
    public function index(Request $request)
    {
        $barcode = $request->query('barcode', '');
        $match = $barcode ? Product::where('barcode', $barcode)->first() : null;

        if ($barcode && ! $match) {
            $request->session()->flash('error', 'No product found with that barcode.');
        }

        return view('purchases.returns', [
            'match' => $match,
            'barcode' => $barcode,
            'returns' => PurchaseReturn::latest('returned_at')->take(30)->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'reason' => ['nullable', 'string'],
            'supplier_name' => ['nullable', 'string'],
        ]);

        $product = Product::findOrFail($data['product_id']);

        if ($data['quantity'] > $product->quantity) {
            return back()->with('error', "Cannot return more than the {$product->quantity} currently in stock.");
        }

        PurchaseReturn::create([
            'product_id' => $product->id,
            'item_name' => $product->name,
            'barcode' => $product->barcode,
            'quantity' => $data['quantity'],
            'unit_cost' => $product->price,
            'reason' => $data['reason'] ?? null,
            'supplier_name' => $data['supplier_name'] ?? null,
            'returned_at' => now(),
        ]);

        $newQuantity = $product->quantity - $data['quantity'];
        $product->update([
            'quantity' => $newQuantity,
            'status' => $newQuantity === 0 ? 'sold' : 'available',
        ]);

        return redirect()->route('purchases.returns.index')->with('success', 'Return logged and stock adjusted.');
    }
}
