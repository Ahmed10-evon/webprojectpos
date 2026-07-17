<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;

/**
 * Refund & Returns — admin-only, per business decision (a salesman could
 * otherwise quietly reverse their own sales).
 */
class RefundController extends Controller
{
    public function index(Request $request)
    {
        $barcode = $request->query('barcode', '');
        $sales = collect();

        if ($barcode) {
            $sales = Sale::with('product')
                ->whereHas('product', fn ($q) => $q->where('barcode', $barcode))
                ->where('status', 'completed')
                ->latest('sold_at')
                ->get();

            if ($sales->isEmpty()) {
                $request->session()->flash('error', 'No active sales history found for this barcode.');
            }
        }

        $recentReturns = Sale::with('product')->where('status', 'refunded')->latest('sold_at')->take(10)->get();

        return view('refund.index', compact('sales', 'barcode', 'recentReturns'));
    }

    public function process(Request $request, Sale $sale)
    {
        if ($sale->status !== 'completed') {
            return back()->with('error', 'This sale was already refunded.');
        }

        $sale->update(['status' => 'refunded']);

        $product = $sale->product;
        if ($product) {
            $product->update([
                'quantity' => $product->quantity + 1,
                'status' => 'available',
            ]);
        }

        return back()->with('success', 'Refund successful! Stock levels updated.');
    }
}
