<?php

namespace App\Livewire\TreatmentComponents\MaternalHealth;

/**
 * @property-write array $lab_tests
 * @property-write array $medications
 * @property-write array $ipt_medications
 * @property-write array $immunization_medications
 */
trait WithMaternalBilling
{
    public array $medical_bill = [
        'registration' => 0,
        'consultation' => 0,
        'rapid_medical_examination' => 0,
        'laboratory_test' => 0,
        'admission' => 0,
        'medical_service' => 0,
        'logistics' => 0,
        'maintenance' => 0,
        'surgical_procedure' => 0,
    ];

    public float $bill_paid = 0;

    public float $bill_outstanding = 0;

    protected function buildLabTestData(): array
    {
        return array_values(array_filter($this->lab_tests, fn ($t) => ! empty($t['name'])));
    }

    protected function buildMedicationData(): array
    {
        return array_values(array_filter($this->medications, fn ($m) => ! empty($m['drug_name'])));
    }

    protected function buildIptMedicationData(): array
    {
        return array_values(array_filter($this->ipt_medications, fn ($m) => ! empty($m['drug_name'])));
    }

    protected function buildImmunizationMedicationData(): array
    {
        return array_values(array_filter($this->immunization_medications, fn ($m) => ! empty($m['drug_name'])));
    }

    public function autoFillMedicalBill(): void
    {
        $this->medical_bill['laboratory_test'] = collect($this->buildLabTestData())->sum('amount');
        $this->medical_bill['medical_service'] = collect($this->buildMedicationData())->sum('amount')
            + collect($this->buildIptMedicationData())->sum('amount')
            + collect($this->buildImmunizationMedicationData())->sum('amount');
        $this->recalculateBill();
    }

    public function recalculateBill(): void
    {
        $total = collect($this->medical_bill)->sum();
        $this->bill_outstanding = $total - $this->bill_paid;
    }

    public function updatedMedicalBill(): void
    {
        $this->recalculateBill();
    }

    public function updatedBillPaid(): void
    {
        $this->recalculateBill();
    }
}
