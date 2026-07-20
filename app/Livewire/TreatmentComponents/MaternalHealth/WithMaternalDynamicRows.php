<?php

namespace App\Livewire\TreatmentComponents\MaternalHealth;

/**
 * @property-write array $prior_pregnancies
 * @property-write array $lab_tests
 * @property-write array $medications
 * @property-write array $ipt_medications
 * @property-write array $immunization_medications
 */
trait WithMaternalDynamicRows
{
    public function addPriorPregnancy(): void
    {
        $this->prior_pregnancies[] = [
            'year' => '', 'gest_age' => '', 'mode_of_delivery' => '',
            'birth_weight' => '', 'complications' => '', 'neonatal_outcome' => '',
        ];
    }

    public function removePriorPregnancy(int $index): void
    {
        unset($this->prior_pregnancies[$index]);
        $this->prior_pregnancies = array_values($this->prior_pregnancies);
    }

    public function addLabTest(): void
    {
        $this->lab_tests[] = ['name' => '', 'specimen' => '', 'amount' => 0, 'attachment' => null];
    }

    public function removeLabTest(int $index): void
    {
        unset($this->lab_tests[$index]);
        $this->lab_tests = array_values($this->lab_tests);
    }

    public function addMedication(): void
    {
        $this->medications[] = ['route_category' => '', 'route_form' => '', 'drug_name' => '', 'strength' => '', 'dosage' => '', 'regime' => '', 'length_value' => '', 'length_unit' => 'days', 'amount' => 0, 'is_take_home' => 1];
    }

    public function removeMedication(int $index): void
    {
        unset($this->medications[$index]);
        $this->medications = array_values($this->medications);
    }

    public function addIptMedication(): void
    {
        $this->ipt_medications[] = ['route_category' => '', 'route_form' => '', 'drug_name' => '', 'strength' => '', 'dosage' => '', 'regime' => '', 'length_value' => '', 'length_unit' => 'days', 'amount' => 0];
    }

    public function removeIptMedication(int $index): void
    {
        unset($this->ipt_medications[$index]);
        $this->ipt_medications = array_values($this->ipt_medications);
    }

    public function addImmunizationMedication(): void
    {
        $this->immunization_medications[] = ['route_category' => '', 'route_form' => '', 'drug_name' => '', 'strength' => '', 'dosage' => '', 'regime' => '', 'length_value' => '', 'length_unit' => 'days', 'amount' => 0];
    }

    public function removeImmunizationMedication(int $index): void
    {
        unset($this->immunization_medications[$index]);
        $this->immunization_medications = array_values($this->immunization_medications);
    }
}
