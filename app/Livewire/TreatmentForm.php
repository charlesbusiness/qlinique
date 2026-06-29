<?php

namespace App\Livewire;

use App\Models\Patient;
use App\Services\TreatmentService;
use Livewire\Component;

class TreatmentForm extends Component
{
    public ?int $patientId = null;
    public string $category = 'checkup';
    public string $other_category = '';
    public string $visit_date = '';
    public string $presenting_complaint = '';
    public string $symptoms = '';
    public string $clinical_notes = '';
    public string $previous_treatment_history = '';
    public string $primary_diagnosis = '';
    public string $secondary_diagnosis = '';
    public string $diagnosis_notes = '';
    public string $treatment_plan = '';
    public string $treatment_plan_value = '';
    public string $treatment_plan_type = 'days';
    public string $take_home_medication = '';

    public array $vitals = [
        'temperature' => null,
        'temperature_unit' => 'celsius',
        'blood_pressure_systolic' => null,
        'blood_pressure_diastolic' => null,
        'pulse_rate' => null,
        'respiratory_rate' => null,
        'weight' => null,
        'height' => null,
        'oxygen_saturation' => null,
        'bmi' => null,
    ];

    public array $medications = [];

    public array $labTests = [];

    public int $step = 1;

    private function temperatureRule(): array
    {
        return [
            'nullable',
            'numeric',
            function ($attribute, $value, $fail) {
                if ($value === null) return;
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

    protected function rules(): array
    {
        return [
            'patientId' => 'required|exists:patients,id',
            'category' => 'required|in:checkup,treatment,emergency,antenatal,consultancy,other',
            'other_category' => 'required_if:category,other|string|max:255',
            'visit_date' => 'required|date',

            'vitals.temperature' => $this->temperatureRule(),
            'vitals.temperature_unit' => 'nullable|in:celsius,fahrenheit',
            'vitals.blood_pressure_systolic' => 'nullable|numeric|min:60|max:250',
            'vitals.blood_pressure_diastolic' => 'nullable|numeric|min:30|max:150',
            'vitals.pulse_rate' => 'nullable|numeric|min:30|max:250',
            'vitals.respiratory_rate' => 'nullable|numeric|min:5|max:60',
            'vitals.weight' => 'nullable|numeric|min:0.5|max:500',
            'vitals.height' => 'nullable|numeric|min:10|max:300',
            'vitals.oxygen_saturation' => 'nullable|numeric|min:50|max:100',
            'vitals.bmi' => 'nullable|numeric|min:5|max:80',
        ];
    }

    public function updatedVitals($value, $key): void
    {
        if (in_array($key, ['weight', 'height'])) {
            $weight = $this->vitals['weight'];
            $height = $this->vitals['height'];

            if ($weight && $height && $height > 0) {
                $this->vitals['bmi'] = round($weight / (($height / 100) ** 2), 1);
            } else {
                $this->vitals['bmi'] = null;
            }
        }
    }

    public function mount(?int $patientId = null): void
    {
        $this->patientId = $patientId;
        $this->visit_date = now()->format('Y-m-d');
    }

    public function nextStep(): void
    {
        $stepRules = [
            1 => [
                'patientId' => 'required|exists:patients,id',
                'category' => 'required|in:checkup,treatment,emergency,antenatal,consultancy,other',
                'other_category' => 'required_if:category,other|string|max:255',
                'visit_date' => 'required|date',
            ],
            2 => [
                'vitals.temperature' => $this->temperatureRule(),
                'vitals.temperature_unit' => 'nullable|in:celsius,fahrenheit',
                'vitals.blood_pressure_systolic' => 'nullable|numeric|min:60|max:250',
                'vitals.blood_pressure_diastolic' => 'nullable|numeric|min:30|max:150',
                'vitals.pulse_rate' => 'nullable|numeric|min:30|max:250',
                'vitals.respiratory_rate' => 'nullable|numeric|min:5|max:60',
                'vitals.weight' => 'nullable|numeric|min:0.5|max:500',
                'vitals.height' => 'nullable|numeric|min:10|max:300',
                'vitals.oxygen_saturation' => 'nullable|numeric|min:50|max:100',
                'vitals.bmi' => 'nullable|numeric|min:5|max:80',
            ],
        ];

        if (isset($stepRules[$this->step])) {
            $this->validate($stepRules[$this->step]);
        }

        $this->step++;
    }

    public function prevStep(): void
    {
        $this->step--;
    }

    public function addMedication(): void
    {
        $this->medications[] = [
            'drug_name' => '',
            'quantity' => 0,
            'unit_cost' => 0,
            'dosage' => '',
            'duration' => '',
            'is_take_home' => false,
        ];
    }

    public function removeMedication(int $index): void
    {
        unset($this->medications[$index]);
        $this->medications = array_values($this->medications);
    }

    public function addLabTest(): void
    {
        $this->labTests[] = [
            'test_type' => '',
            'cost' => 0,
        ];
    }

    public function removeLabTest(int $index): void
    {
        unset($this->labTests[$index]);
        $this->labTests = array_values($this->labTests);
    }

    public function save(TreatmentService $treatmentService): void
    {
        $this->validate();

        $data = [
            'patient_id' => $this->patientId,
            'category' => $this->category,
            'other_category' => $this->other_category ?: null,
            'visit_date' => $this->visit_date,
            'presenting_complaint' => $this->presenting_complaint ?: null,
            'symptoms' => $this->symptoms ?: null,
            'clinical_notes' => $this->clinical_notes ?: null,
            'previous_treatment_history' => $this->previous_treatment_history ?: null,
            'primary_diagnosis' => $this->primary_diagnosis ?: null,
            'secondary_diagnosis' => $this->secondary_diagnosis ?: null,
            'diagnosis_notes' => $this->diagnosis_notes ?: null,
            'treatment_plan' => $this->treatment_plan ?: null,
            'treatment_schedule' => $this->treatment_plan_value
                ? $this->treatment_plan_value . '/' . $this->treatment_plan_type
                : null,
            'take_home_medication' => $this->take_home_medication ?: null,
            'vitals' => array_filter($this->vitals, fn($v) => !is_null($v)),
            'medications' => array_filter($this->medications, fn($m) => !empty($m['drug_name'])),
            'lab_tests' => array_filter($this->labTests, fn($l) => !empty($l['test_type'])),
        ];

        $treatmentService->create($data);

        session()->flash('status', 'Treatment chart created successfully.');
        $this->redirect(route('treatments.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.treatment-form', [
            'patients' => Patient::where('is_active', true)->orderBy('name')->get(),
        ]);
    }
}
