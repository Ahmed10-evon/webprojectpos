<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\TaxRate;
use Illuminate\Http\Request;

class TaxRateController extends Controller
{
    public function index()
    {
        return view('settings.tax', ['taxRates' => TaxRate::orderBy('name')->get()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string'],
            'rate_percent' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        TaxRate::create($data);

        return back()->with('success', 'Tax rate added.');
    }

    public function destroy(TaxRate $taxRate)
    {
        $taxRate->delete();

        return back()->with('success', 'Tax rate removed.');
    }
}
