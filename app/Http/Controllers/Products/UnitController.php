<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index()
    {
        return view('products.units', ['units' => Unit::orderBy('name')->get()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'unique:units,name'],
            'short_code' => ['nullable', 'string'],
        ]);
        Unit::create($data);

        return back()->with('success', 'Unit added.');
    }

    public function destroy(Unit $unit)
    {
        $unit->delete();

        return back()->with('success', 'Unit removed.');
    }
}
