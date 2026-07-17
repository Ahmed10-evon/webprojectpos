<?php

namespace App\Http\Controllers;

use App\Models\DailyCost;
use App\Models\Sale;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Financial reports — admin-only. This is exactly the kind of revenue /
 * net-profit view the Salesman role is meant to be kept away from.
 */
class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $sales = Sale::with('product')
            ->when($startDate, fn ($q) => $q->whereDate('sold_at', '>=', $startDate))
            ->when($endDate, fn ($q) => $q->whereDate('sold_at', '<=', $endDate))
            ->latest('sold_at')
            ->get();

        $completed = $sales->where('status', 'completed');
        $totalRevenue = $completed->sum('amount_paid');

        $methods = ['cash' => 0, 'bkash' => 0, 'nagad' => 0, 'upay' => 0, 'rocket' => 0, 'bank/card' => 0];
        foreach ($completed as $sale) {
            $displayMethod = $sale->transaction_id === 'BANK/CARD-SALE' ? 'bank/card' : $sale->payment_method;
            if (array_key_exists($displayMethod, $methods)) {
                $methods[$displayMethod] += $sale->amount_paid;
            }
        }

        $costsQuery = DailyCost::query()
            ->when($startDate, fn ($q) => $q->whereDate('cost_date', '>=', $startDate))
            ->when($endDate, fn ($q) => $q->whereDate('cost_date', '<=', $endDate));
        $totalCosts = (clone $costsQuery)->sum('amount');
        $costsCount = (clone $costsQuery)->count();
        $netProfit = $totalRevenue - $totalCosts;

        return view('reports.index', compact(
            'sales', 'totalRevenue', 'methods', 'totalCosts', 'costsCount',
            'netProfit', 'startDate', 'endDate'
        ));
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $sales = Sale::with('product')
            ->when($startDate, fn ($q) => $q->whereDate('sold_at', '>=', $startDate))
            ->when($endDate, fn ($q) => $q->whereDate('sold_at', '<=', $endDate))
            ->latest('sold_at')
            ->get();

        $filename = 'space-topup-ledger-'.now()->format('Y-m-d').'.csv';

        return response()->streamDownload(function () use ($sales) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Date', 'Item', 'Barcode', 'Method', 'Status', 'Amount (BDT)']);
            foreach ($sales as $sale) {
                fputcsv($out, [
                    $sale->sold_at->format('Y-m-d H:i:s'),
                    $sale->product?->name,
                    $sale->product?->barcode,
                    $sale->transaction_id === 'BANK/CARD-SALE' ? 'bank/card' : $sale->payment_method,
                    $sale->status,
                    $sale->amount_paid,
                ]);
            }
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}
