<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Services\CurrencyService;
use App\Services\TimezoneService;
use App\Services\WeatherService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        protected WeatherService $weather,
        protected TimezoneService $timezone,
        protected CurrencyService $currency,
    ) {
    }

    public function index(Request $request)
    {
        $user = $request->user();

        $weather = $this->weather->current();
        $timezone = $this->timezone->current();
        $currency = $this->currency->rates();

        $todayRevenue = Sale::whereDate('sold_at', today())
            ->where('status', 'completed')
            ->sum('amount_paid');

        // 1 USD = X BDT (from the currency API) — used to also show today's
        // revenue converted to USD on the dashboard tile.
        $usdRate = $currency['rates']['BDT'] ?? null;
        $todayRevenueUsd = $usdRate ? $todayRevenue / $usdRate : null;

        $todayItemsSold = Sale::whereDate('sold_at', today())
            ->where('status', 'completed')
            ->count();

        $activeStock = Product::where('status', '!=', 'archived')->get();
        // Each product has its own reorder_level now (see Low Stock Alert),
        // rather than one fixed number for the whole shop.
        $lowStockItems = $activeStock
            ->filter(fn ($item) => $item->isLowStock())
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
            'todayRevenue', 'todayRevenueUsd', 'todayItemsSold', 'lowStockItems',
            'outOfStockCount', 'recentSales', 'topSellers', 'weather', 'timezone', 'currency'
        ));
    }
}
