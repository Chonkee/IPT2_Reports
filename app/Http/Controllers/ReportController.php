<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Transaction;
use Illuminate\Http\Request;
use PDF;

class ReportController extends Controller
{
    public function index()
    {
        $reports = Report::latest()->get();

        return view('reports.index', compact('reports'));
    }

    public function create()
    {
        return view('reports.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2020|max:' . (date('Y') + 1),
        ]);

        // Check if report already exists for this month/year
        $existing = Report::where('month', $request->month)
                          ->where('year', $request->year)
                          ->first();

        if ($existing) {
            return redirect()->route('reports.index')->with('error', 'Report for this month already exists.');
        }

        Report::create([
            'month' => $request->month,
            'year' => $request->year,
        ]);

        return redirect()->route('reports.index')->with('success', 'Report generated successfully.');
    }

    public function show(Report $report)
    {
        $transactions = Transaction::with('account.customer')
            ->whereYear('transaction_date', $report->year)
            ->whereMonth('transaction_date', $report->month)
            ->orderBy('transaction_date')
            ->get();

        return view('reports.show', compact('report', 'transactions'));
    }

    public function downloadPDF(Report $report)
    {
        $transactions = Transaction::with('account.customer')
            ->whereYear('transaction_date', $report->year)
            ->whereMonth('transaction_date', $report->month)
            ->orderBy('transaction_date')
            ->get();

        $pdf = PDF::loadView('reports.pdf', compact('report', 'transactions'));

        return $pdf->download('report_' . $report->month_name . '_' . $report->year . '.pdf');
    }
}
