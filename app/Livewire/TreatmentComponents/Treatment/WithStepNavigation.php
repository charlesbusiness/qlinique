<?php

namespace App\Livewire\TreatmentComponents\Treatment;

/**
 * @property-read array $vitals
 * @property-read bool $consent_enabled
 * @property-read array $consent
 */
trait WithStepNavigation
{
    public array $stepLabels = [
        1 => 'Patient & History',
        2 => 'Vital Signs',
        3 => 'Physical Exam 1',
        4 => 'Physical Exam 2',
        5 => 'Investigation & Diagnosis',
        6 => 'Treatment Plan',
        7 => 'Billing',
    ];

    private function temperatureRule(): array
    {
        return [
            'required',
            'numeric',
            function ($attribute, $value, $fail) {
                $unit = $this->vitals['temperature_unit'] ?? 'celsius';
                if ($unit === 'fahrenheit') {
                    if ($value < 93 || $value > 108) {
                        $fail('Temperature must be between 93°F and 108°F.');
                    }
                } else {
                    if ($value < 34 || $value > 43) {
                        $fail('Temperature must be between 34°C and 43°C.');
                    }
                }
            },
        ];
    }

    protected function stepRules(): array
    {
        return [
            1 => [
                'finding_on_history' => 'required|string',
                'previous_treatment_history' => 'required|string',
                'recommended_drugs' => 'required|string',
                'allergies' => 'required|string',
            ],
            2 => [
                'vitals.temperature' => $this->temperatureRule(),
                'vitals.blood_pressure_systolic' => 'required|numeric',
                'vitals.blood_pressure_diastolic' => 'required|numeric',
                'vitals.pulse_rate' => 'required|numeric|min:0|max:300',
                'vitals.respiratory_rate' => 'required|numeric|min:0|max:100',
                'vitals.oxygen_saturation' => 'required|numeric|min:0|max:100',
                'vitals.comment' => 'nullable|string',
            ],
            3 => [
                'vitals.weight' => 'required|numeric|min:0',
                'vitals.height' => 'required|numeric|min:0',
            ],
            6 => [
                'consent.procedure_description' => 'required_if:consent_enabled,true|string|max:500',
                'consent.attending_physician' => 'required_if:consent_enabled,true|string|max:255',
                'consent.patient_signature_type' => 'required_if:consent_enabled,true|in:typed,drawn,uploaded',
                'consent.patient_signature' => 'required_if:consent.patient_signature_type,typed|string|max:255',
                'consent.witness_name' => 'required_if:consent_enabled,true|string|max:255',
                'consent.witness_signature_type' => 'required_if:consent_enabled,true|in:typed,drawn,uploaded',
                'consent.witness_signature' => 'required_if:consent.witness_signature_type,typed|string|max:255',
                'consent.physician_signature_type' => 'required_if:consent_enabled,true|in:typed,drawn,uploaded',
                'consent.physician_signature' => 'required_if:consent.physician_signature_type,typed|string|max:255',
            ],
            7 => [],
        ];
    }

    protected function validationAttributes(): array
    {
        return [
            'vitals.temperature' => 'Temperature',
            'vitals.blood_pressure_systolic' => 'Blood Pressure Systolic',
            'vitals.blood_pressure_diastolic' => 'Blood Pressure Diastolic',
            'vitals.pulse_rate' => 'Pulse Rate',
            'vitals.respiratory_rate' => 'Respiratory Rate',
            'vitals.weight' => 'Weight',
            'vitals.height' => 'Height',
            'vitals.oxygen_saturation' => 'Oxygen Saturation',
            'vitals.comment' => 'Comment',
        ];
    }

    public function nextStep(): void
    {
        $rules = $this->stepRules();
        if (isset($rules[$this->step]) && ! empty($rules[$this->step])) {
            $this->validate($rules[$this->step]);
        }

        $this->saveDraft();

        if ($this->step === 7) {
            $this->publish();

            return;
        }

        $this->step++;

        if (in_array($this->step, [6, 7])) {
            $this->autoFillMedicalBill();
        }
    }

    public function prevStep(): void
    {
        if ($this->step > 1) {
            $this->step--;
        }
    }

    public function setConsentSigType(string $field, string $type): void
    {
        $this->consent[$field] = $type;
    }
}
