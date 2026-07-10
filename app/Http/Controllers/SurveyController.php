<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SurveyRecord;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    public function index()
    {
        $records = SurveyRecord::orderBy('record_date')->take(10)->get();

        return view('survey.index', ['records' => $records]);
    }

    public function sync()
    {
        // Pulls the last 500 completed sales and rebuilds day-totals from
        // real transactions, replacing whatever manual entries exist for
        // those same days.
        $sales = Sale::where('status', 'completed')->latest('sold_at')->take(500)->get();

        $dayTotals = [];
        foreach ($sales as $sale) {
            $day = $sale->sold_at->toDateString();
            $dayTotals[$day] = ($dayTotals[$day] ?? 0) + (float) $sale->amount_paid;
        }

        ksort($dayTotals);
        $lastTen = array_slice($dayTotals, -10, 10, true);

        foreach ($lastTen as $date => $amount) {
            SurveyRecord::updateOrCreate(['record_date' => $date], ['amount' => round($amount)]);
        }

        return back()->with('success', 'Synced from real sales.');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'record_date' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'min:0'],
        ]);

        SurveyRecord::updateOrCreate(['record_date' => $data['record_date']], ['amount' => round($data['amount'])]);

        return back()->with('success', "Entry saved for {$data['record_date']}.");
    }

    public function destroy(SurveyRecord $surveyRecord)
    {
        $surveyRecord->delete();

        return back()->with('success', 'Removed.');
    }
}
