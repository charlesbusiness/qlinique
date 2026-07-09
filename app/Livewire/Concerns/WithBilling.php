<?php

namespace App\Livewire\Concerns;

use App\Models\TreatmentChart;

trait WithBilling
{
    public function autoFillMedicalBill(): void
    {
        $this->medicalBill['rapid_medical_examination'] = collect($this->rmeResults)->sum('amount');
        $this->medicalBill['laboratory_test'] = collect($this->labTests)->sum('amount');
        $this->medicalBill['medical_service'] = collect($this->treatmentPlanItems)->sum('amount');

        $this->recalculateTotal();
    }

    private function stripExtraBillKeys(): array
    {
        return array_diff_key($this->medicalBill, array_flip(['total', 'paid', 'outstanding', 'previous_outstanding']));
    }

    public function recalculateTotal(): void
    {
        if ($this->patientId) {
            $this->previousOutstanding = TreatmentChart::where('patient_id', $this->patientId)
                ->where('id', '!=', $this->draftId)
                ->where('is_draft', false)
                ->get()
                ->sum(fn($chart) => ($chart->medical_bill['total'] ?? 0) - ($chart->medical_bill['paid'] ?? 0));
        }

        $this->billTotal = array_sum($this->stripExtraBillKeys());
        $this->billOutstanding = $this->previousOutstanding + ($this->billTotal - $this->billPaid);
    }

    public function updatedMedicalBill(): void
    {
        $this->recalculateTotal();
    }

    public function updatedBillPaid(): void
    {
        $this->recalculateTotal();
    }

    public function lengthDisplay(int $value, string $unit): string
    {
        return match ($unit) {
            'days' => "{$value}/7",
            'weeks' => "{$value}/52",
            'months' => "{$value}/12",
            default => "{$value}/7",
        };
    }
}
