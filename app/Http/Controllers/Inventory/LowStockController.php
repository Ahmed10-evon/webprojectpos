<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

/**
 * Low Stock Alert — open to both Admin and Salesman (it's informational,
 * same as List Products), but only Admin sees the "Request Restock" button
 * since that leads into the admin-only Purchase Requisition module.
 */
class LowStockController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->query('filter', 'low'); // low | out | all

        $products = Product::where('status', '!=', 'archived')->get();

        $lowStock = $products->filter(fn ($p) => $p->isLowStock());
        $outOfStock = $products->filter(fn ($p) => $p->isOutOfStock());

        $items = match ($filter) {
            'out' => $outOfStock,
            'all' => $lowStock->merge($outOfStock),
            default => $lowStock,
        };

        return view('inventory.low-stock', [
            'items' => $items->sortBy('quantity')->values(),
            'filter' => $filter,
            'lowCount' => $lowStock->count(),
            'outCount' => $outOfStock->count(),
        ]);
    }
}
