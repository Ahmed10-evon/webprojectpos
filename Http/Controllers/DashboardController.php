<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    const LOW_STOCK_THRESHOLD = 3;

    public function index(Request $request)
    {
        $user = $request->user();

        $todayRevenue = Sale::whereDate('sold_at', today())
            ->where('status', 'completed')
            ->sum('amount_paid');

        $todayItemsSold = Sale::whereDate('sold_at', today())
            ->where('status', 'completed')
            ->count();

        $activeStock = Product::where('status', '!=', 'archived')->get();
        $lowStockItems = $activeStock
            ->filter(fn ($item) => $item->quantity > 0 && $item->quantity <= self::LOW_STOCK_THRESHOLD)
            ->sortBy('quantity')
            ->take(6);
        $outOfStockCount = $activeStock->where('quantity', 0)->count();

        $recentSales = Sale::with('product')->latest('sold_at')->take(6)->get();

        // Top sellers is a financial-ish leaderboard (units + revenue) — kept
        // for admins only; salesmen still see today's numbers and low stock.
        $topSellers = [];
        if ($user->isAdmin()) {
            $topSellers = Sale::with('product')
                ->where('status', 'completed')
                ->latest('sold_at')
                ->take(500)
                ->get()
                ->groupBy('product_id')
                ->map(function ($sales) {
                    $product = $sales->first()->product;

                    return [
                        'name' => $product?->name ?? 'Unknown Item',
                        'barcode' => $product?->barcode ?? '',
                        'units_sold' => $sales->count(),
                        'revenue' => $sales->sum('amount_paid'),
                    ];
                })
                ->sortByDesc('units_sold')
                ->take(5)
                ->values();
        }

        return view('dashboard.index', compact(
            'todayRevenue', 'todayItemsSold', 'lowStockItems',
            'outOfStockCount', 'recentSales', 'topSellers'
        ));
    }
}
