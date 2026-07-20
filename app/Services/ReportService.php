<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Patient;
use App\Models\TreatmentChart;
use Carbon\Carbon;

class ReportService
{
    public function dailyReport(?Carbon $date = null): array
    {
        $date = $date ?? now();

        return [
            'new_patients' => Patient::whereDate('created_at', $date)->count(),
            'treatments' => TreatmentChart::whereDate('visit_date', $date)->count(),
            'emergencies' => TreatmentChart::whereDate('visit_date', $date)->where('category', 'emergency_accident')->count(),
            'revenue' => Invoice::whereDate('created_at', $date)->sum('amount_paid'),
        ];
    }

    public function treatmentReport(?Carbon $from = null, ?Carbon $to = null): array
    {
        $from = $from ?? now()->startOfMonth();
        $to = $to ?? now()->endOfMonth();

        $treatments = TreatmentChart::whereBetween('visit_date', [$from, $to]);

        return [
            'total' => (clone $treatments)->count(),
            'by_category' => [
                'checkup' => (clone $treatments)->where('category', 'checkup')->count(),
                'treatment' => (clone $treatments)->where('category', 'treatment')->count(),
                'maternal_health' => (clone $treatments)->where('category', 'maternal_health')->count(),
                'enrollment_palliative' => (clone $treatments)->where('category', 'enrollment_palliative')->count(),
                'emergency_accident' => (clone $treatments)->where('category', 'emergency_accident')->count(),
                'consultancy' => (clone $treatments)->where('category', 'consultancy')->count(),
            ],
            'completed' => (clone $treatments)->where('is_completed', true)->count(),
        ];
    }

    public function financialReport(?Carbon $from = null, ?Carbon $to = null): array
    {
        $from = $from ?? now()->startOfMonth();
        $to = $to ?? now()->endOfMonth();

        $invoices = Invoice::whereBetween('created_at', [$from, $to]);

        return [
            'total_invoiced' => (clone $invoices)->sum('amount_due'),
            'total_collected' => (clone $invoices)->sum('amount_paid'),
            'outstanding' => (clone $invoices)->sum('balance'),
            'count' => (clone $invoices)->count(),
        ];
    }
}
