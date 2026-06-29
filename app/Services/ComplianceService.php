<?php

namespace App\Services;

use App\Models\TreatmentChart;

class ComplianceService
{
    public function getComplianceReport(?string $period = null): array
    {
        $query = TreatmentChart::where('is_completed', false)
            ->whereNotNull('treatment_schedule')
            ->with('patient', 'complianceLogs');

        return [
            'total_active' => (clone $query)->count(),
            'compliant' => (clone $query)->get()->filter(fn($t) => $t->compliancePercentage() >= 75)->count(),
            'non_compliant' => (clone $query)->get()->filter(fn($t) => $t->compliancePercentage() < 75)->count(),
        ];
    }
}
