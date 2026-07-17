<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockAdjustment;
use Illuminate\Http\Request;

/**
 * Stock Adjustment — the missing half of Inventory Management: correcting
 * stock for reasons that are neither a sale nor a purchase (damage, theft,
 * a physical recount, expiry). Admin-only, since it changes stock levels
 * without the paper trail a sale/purchase normally provides.
 */
class StockAdjustmentController extends Controller
{
    const REASONS = ['damaged', 'theft_loss', 'recount_correction', 'expired', 'other'];

    public function index(Request $request)
    {
        $search = $request->query('q', '');

        $matches = $search === '' ? collect() : Product::where('status', '!=', 'archived')
            ->where(fn ($q) => $q->where('name', 'like', "%{$search}%")
                ->orWhere('barcode', 'like', "%{$search}%"))
            ->orderBy('name')
            ->limit(20)
            ->get();

        $recent = StockAdjustment::with(['product', 'user'])->latest('adjusted_at')->take(15)->get();

        return view('inventory.adjustments', [
            'matches' => $matches,
            'search' => $search,
            'recent' => $recent,
            'reasons' => self::REASONS,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'direction' => ['required', 'in:increase,decrease'],
            'quantity' => ['required', 'integer', 'min:1'],
            'reason' => ['required', 'in:'.implode(',', self::REASONS)],
            'notes' => ['nullable', 'string', 'max:255'],
        ]);

        $product = Product::findOrFail($data['product_id']);
        $before = $product->quantity;

        if ($data['direction'] === 'decrease' && $data['quantity'] > $before) {
            return back()->with('error', "Cannot decrease by {$data['quantity']} — only {$before} currently in stock.");
        }

        $after = $data['direction'] === 'increase'
            ? $before + $data['quantity']
            : $before - $data['quantity'];

        StockAdjustment::create([
            'product_id' => $product->id,
            'user_id' => $request->user()->id,
            'direction' => $data['direction'],
            'quantity' => $data['quantity'],
            'quantity_before' => $before,
            'quantity_after' => $after,
            'reason' => $data['reason'],
            'notes' => $data['notes'] ?? null,
            'adjusted_at' => now(),
        ]);

        $product->update([
            'quantity' => $after,
            'status' => $after === 0 ? 'sold' : 'available',
        ]);

        return redirect()->route('inventory.adjustments.index')
            ->with('success', "Stock adjusted: {$product->name} is now at {$after}.");
    }
}
