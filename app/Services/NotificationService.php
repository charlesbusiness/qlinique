<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\TreatmentChart;

class NotificationService
{
    public function missedTreatmentAlerts(): array
    {
        $alerts = [];
        $treatments = TreatmentChart::where('is_completed', false)
            ->whereNotNull('treatment_schedule')
            ->with('patient')
            ->get();

        foreach ($treatments as $treatment) {
            $missed = $treatment->missedSessions();
            if ($missed->count() > 0) {
                $alerts[] = [
                    'type' => 'missed_treatment',
                    'message' => "{$treatment->patient->name} has missed {$missed->count()} session(s).",
                    'patient_id' => $treatment->patient_id,
                    'treatment_id' => $treatment->id,
                ];
            }
        }

        return $alerts;
    }

    public function overduePaymentAlerts(): array
    {
        $alerts = [];
        $invoices = Invoice::whereIn('status', ['pending', 'partial'])
            ->where('balance', '>', 0)
            ->with('patient')
            ->get();

        foreach ($invoices as $invoice) {
            $alerts[] = [
                'type' => 'overdue_payment',
                'message' => "Outstanding balance of {$invoice->balance} for {$invoice->patient->name}.",
                'patient_id' => $invoice->patient_id,
                'invoice_id' => $invoice->id,
            ];
        }

        return $alerts;
    }
}
