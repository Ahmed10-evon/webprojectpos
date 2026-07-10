<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use Illuminate\Http\Request;

class AllSalesController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('q', '');

        $sales = Sale::with('product')
            ->when($search, fn ($q) => $q->whereHas('product', fn ($d) => $d
                ->where('name', 'like', "%{$search}%")
                ->orWhere('barcode', 'like', "%{$search}%")))
            ->latest('sold_at')
            ->paginate(30)
            ->withQueryString();

        return view('sales.all', ['sales' => $sales, 'search' => $search]);
    }
}
