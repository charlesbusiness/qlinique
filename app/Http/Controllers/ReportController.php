<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct(
        protected ReportService $reportService
    ) {}

    public function daily(Request $request)
    {
        $date = $request->date ? Carbon::parse($request->date) : now();
        $report = $this->reportService->dailyReport($date);

        return view('reports.daily', compact('report', 'date'));
    }

    public function treatment(Request $request)
    {
        $from = $request->from ? Carbon::parse($request->from) : now()->startOfMonth();
        $to = $request->to ? Carbon::parse($request->to) : now()->endOfMonth();
        $report = $this->reportService->treatmentReport($from, $to);

        return view('reports.treatment', compact('report', 'from', 'to'));
    }

    public function compliance()
    {
        return view('reports.compliance');
    }

    public function financial(Request $request)
    {
        $from = $request->from ? Carbon::parse($request->from) : now()->startOfMonth();
        $to = $request->to ? Carbon::parse($request->to) : now()->endOfMonth();
        $report = $this->reportService->financialReport($from, $to);

        return view('reports.financial', compact('report', 'from', 'to'));
    }
}
