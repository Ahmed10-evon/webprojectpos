<?php

namespace App\Http\Controllers;

use App\Models\DailyCost;
use Illuminate\Http\Request;

class DailyCostController extends Controller
{
    public function index()
    {
        $costs = DailyCost::latest('cost_date')->take(200)->get();

        $todayStr = now()->toDateString();
        $monthStr = now()->format('Y-m');

        $todaysCosts = $costs->where('cost_date', $todayStr);
        $monthCosts = $costs->filter(fn ($c) => str_starts_with($c->cost_date->toDateString(), $monthStr));

        return view('daily-cost.index', [
            'costs' => $costs,
            'todaysTotal' => $todaysCosts->sum('amount'),
            'todaysCount' => $todaysCosts->count(),
            'monthTotal' => $monthCosts->sum('amount'),
            'monthCount' => $monthCosts->count(),
            'allTimeTotal' => $costs->sum('amount'),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'cost_date' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'note' => ['required', 'string'],
        ]);

        DailyCost::create($data);

        return back()->with('success', 'Cost recorded.');
    }

    public function destroy(DailyCost $dailyCost)
    {
        $dailyCost->delete();

        return back()->with('success', 'Removed.');
    }
}
