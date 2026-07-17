<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;

/**
 * Search-based single-item quick sale — used when there's no barcode to
 * scan. Same permission level as the POS: both Admin and Salesman.
 */
class AddSaleController extends Controller
{
    const PAYMENT_METHODS = ['cash', 'bkash', 'nagad', 'upay', 'rocket', 'bank/card'];

    public function index(Request $request)
    {
        $search = $request->query('q', '');

        $results = $search === '' ? collect() : Product::where('status', '!=', 'archived')
            ->where('quantity', '>', 0)
            ->where(fn ($q) => $q->where('name', 'like', "%{$search}%")
                ->orWhere('barcode', 'like', "%{$search}%"))
            ->limit(30)
            ->get();

        return view('sales.add', [
            'results' => $results,
            'search' => $search,
            'paymentMethods' => self::PAYMENT_METHODS,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'payment_method' => ['required', 'in:'.implode(',', self::PAYMENT_METHODS)],
            'trx_id' => ['nullable', 'string'],
        ]);

        $product = Product::findOrFail($data['product_id']);

        $dbPaymentMethod = $data['payment_method'] === 'bank/card' ? 'cash' : $data['payment_method'];
        $dbTrxId = match (true) {
            $data['payment_method'] === 'bank/card' => 'BANK/CARD-SALE',
            $data['payment_method'] === 'cash' => 'DIRECT-SALE',
            default => $data['trx_id'] ?? null,
        };

        Sale::create([
            'product_id' => $product->id,
            'user_id' => $request->user()->id,
            'payment_method' => $dbPaymentMethod,
            'transaction_id' => $dbTrxId,
            'amount_paid' => $product->price,
            'status' => 'completed',
            'sold_at' => now(),
        ]);

        $newQuantity = $product->quantity - 1;
        $product->update([
            'quantity' => max($newQuantity, 0),
            'status' => $newQuantity <= 0 ? 'sold' : 'available',
        ]);

        return redirect()->route('sales.add')->with('success', "Sale recorded for {$product->name}.");
    }
}
