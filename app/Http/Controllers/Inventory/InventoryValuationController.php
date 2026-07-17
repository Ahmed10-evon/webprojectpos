<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Product;

/**
 * Inventory Valuation — total worth of everything currently on the shelf
 * (quantity x selling price, per item and shop-wide). Admin-only, same
 * sensitivity level as Reports.
 */
class InventoryValuationController extends Controller
{
    public function index()
    {
        $products = Product::where('status', '!=', 'archived')
            ->orderByDesc('quantity')
            ->get()
            ->map(function ($p) {
                $p->stock_value = $p->quantity * $p->price;

                return $p;
            });

        $totalValue = $products->sum('stock_value');
        $totalUnits = $products->sum('quantity');

        $byCategory = $products->groupBy('category')->map(fn ($group) => [
            'units' => $group->sum('quantity'),
            'value' => $group->sum('stock_value'),
        ])->sortByDesc('value');

        return view('inventory.valuation', compact('products', 'totalValue', 'totalUnits', 'byCategory'));
    }
}
