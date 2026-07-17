<?php

namespace App\Http\Controllers\Purchases;

use App\Http\Controllers\Controller;
use App\Models\Purchase;

class PurchaseListController extends Controller
{
    public function index()
    {
        return view('purchases.list', [
            'purchases' => Purchase::latest('purchased_at')->paginate(30),
        ]);
    }
}
