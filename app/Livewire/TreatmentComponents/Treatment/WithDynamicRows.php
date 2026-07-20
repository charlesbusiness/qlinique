<?php

namespace App\Livewire\TreatmentComponents\Treatment;

/**
 * @property-write array $labTests
 * @property-write array $labTestUploads
 * @property-write array $treatmentPlanItems
 * @property-write array $rmeResults
 * @property-write string $rmeNewTest
 */
trait WithDynamicRows
{
    public function addLabTest(): void
    {
        $this->labTests[] = ['test_type' => '', 'sample_type' => '', 'amount' => 0, 'findings' => ''];
    }

    public function removeLabTest(int $index): void
    {
        unset($this->labTests[$index], $this->labTestUploads[$index]);
        $this->labTests = array_values($this->labTests);
        $this->labTestUploads = array_values($this->labTestUploads);
    }

    public function addTreatmentPlanItem(): void
    {
        $this->treatmentPlanItems[] = [
            'route_category' => '',
            'route_form' => '',
            'drug_name' => '',
            'strength' => '',
            'dosage' => '',
            'regime' => 'dly',
            'length_value' => 1,
            'length_unit' => 'days',
            'length_display' => $this->lengthDisplay(1, 'days'),
            'amount' => 0,
            'is_take_home' => false,
        ];
    }

    public function removeTreatmentPlanItem(int $index): void
    {
        unset($this->treatmentPlanItems[$index]);
        $this->treatmentPlanItems = array_values($this->treatmentPlanItems);
    }

    public function addRmeTest(): void
    {
        if (! $this->rmeNewTest) {
            return;
        }

        $exists = collect($this->rmeResults)->contains('test_name', $this->rmeNewTest);
        if (! $exists) {
            $this->rmeResults[] = [
                'test_name' => $this->rmeNewTest,
                'result' => '',
                'amount' => 0,
            ];
        }
        $this->rmeNewTest = '';
    }

    public function removeRmeTest(int $index): void
    {
        unset($this->rmeResults[$index]);
        $this->rmeResults = array_values($this->rmeResults);
    }
}
