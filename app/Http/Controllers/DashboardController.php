<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\TreatmentChart;
use App\Models\Invoice;
use App\Services\ComplianceService;

class DashboardController extends Controller
{
    public function __invoke(ComplianceService $complianceService)
    {
        $user = request()->user();

        $stats = [];

        if ($user->hasPermission('patients.view')) {
            $stats['total_patients'] = Patient::count();
        }

        if ($user->hasPermission('treatments.view')) {
            $stats['active_treatments'] = TreatmentChart::where('is_completed', false)->count();
            $stats['today_treatments'] = TreatmentChart::whereDate('visit_date', today())->count();
        }

        if ($user->hasPermission('finance.invoices.view')) {
            $stats['pending_invoices'] = Invoice::whereIn('status', ['pending', 'partial'])->count();
            $stats['pending_revenue'] = Invoice::whereIn('status', ['pending', 'partial'])->sum('balance');
        }

        $compliance = $user->hasPermission('treatments.compliance')
            ? $complianceService->getComplianceReport()
            : null;

        $recentPatients = $user->hasPermission('patients.view')
            ? Patient::latest()->take(5)->get()
            : collect();

        $recentTreatments = $user->hasPermission('treatments.view')
            ? TreatmentChart::with('patient')->latest()->take(5)->get()
            : collect();

        return view('dashboard.index', compact('stats', 'compliance', 'recentPatients', 'recentTreatments'));
    }
}
