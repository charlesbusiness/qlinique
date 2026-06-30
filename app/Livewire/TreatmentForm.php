<?php

namespace App\Livewire;

use App\Models\Patient;
use App\Services\TreatmentService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class TreatmentForm extends Component
{
    use WithFileUploads;
    public ?int $patientId = null;
    public string $category = 'checkup';
    public string $other_category = '';
    public string $sub_category = '';
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

    public array $consent = [
        'procedure_description' => '',
        'attending_physician' => '',
        'patient_signature_type' => '',
        'patient_signature' => '',
        'witness_name' => '',
        'witness_signature_type' => '',
        'witness_signature' => '',
        'physician_signature_type' => '',
        'physician_signature' => '',
    ];

    public $consent_upload_patient = null;
    public $consent_upload_witness = null;
    public $consent_upload_physician = null;

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
            'category' => 'required|in:checkup,treatment,emergency,antenatal,consultancy,enrollment_treatment_management,other',
            'other_category' => 'required_if:category,other|string|max:255',
            'sub_category' => 'nullable|required_if:category,checkup,treatment,enrollment_treatment_management|in:annual_comprehensive,periodic,employment,traveling,mild_ailments,palliative_care,home_based_care,age_related_care,hypertension,diabetes,hypertension_diabetes,therapeutic_care_asthma',
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

            'consent.procedure_description' => 'required|string|max:500',
            'consent.attending_physician' => 'required|string|max:255',
            'consent.patient_signature_type' => 'required|in:typed,uploaded',
            'consent.patient_signature' => 'required_if:consent.patient_signature_type,typed|string|max:255',
            'consent.witness_name' => 'required|string|max:255',
            'consent.witness_signature_type' => 'required|in:typed,uploaded',
            'consent.witness_signature' => 'required_if:consent.witness_signature_type,typed|string|max:255',
            'consent.physician_signature_type' => 'required|in:typed,uploaded',
            'consent.physician_signature' => 'required_if:consent.physician_signature_type,typed|string|max:255',
        ];
    }

    public function updatedCategory(): void
    {
        $this->sub_category = '';
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
        $this->consent['attending_physician'] = Auth::user()?->name ?? '';
    }

    public function nextStep(): void
    {
        $stepRules = [
            1 => [
                'patientId' => 'required|exists:patients,id',
                'category' => 'required|in:checkup,treatment,emergency,antenatal,consultancy,enrollment_treatment_management,other',
                'other_category' => 'required_if:category,other|string|max:255',
                'sub_category' => 'nullable|required_if:category,checkup,treatment,enrollment_treatment_management|in:annual_comprehensive,periodic,employment,traveling,mild_ailments,palliative_care,home_based_care,age_related_care,hypertension,diabetes,hypertension_diabetes,therapeutic_care_asthma',
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
            5 => [
                'consent.procedure_description' => 'required|string|max:500',
                'consent.attending_physician' => 'required|string|max:255',
                'consent.patient_signature_type' => 'required|in:typed,uploaded',
                'consent.patient_signature' => 'required_if:consent.patient_signature_type,typed|string|max:255',
                'consent.witness_name' => 'required|string|max:255',
                'consent.witness_signature_type' => 'required|in:typed,uploaded',
                'consent.witness_signature' => 'required_if:consent.witness_signature_type,typed|string|max:255',
                'consent.physician_signature_type' => 'required|in:typed,uploaded',
                'consent.physician_signature' => 'required_if:consent.physician_signature_type,typed|string|max:255',
            ],
        ];

        if ($this->step === 5) {
            $this->validate(array_merge($stepRules[5], [
                'consent_upload_patient' => $this->consent['patient_signature_type'] === 'uploaded'
                    ? 'required|image|max:2048' : 'nullable',
                'consent_upload_witness' => $this->consent['witness_signature_type'] === 'uploaded'
                    ? 'required|image|max:2048' : 'nullable',
                'consent_upload_physician' => $this->consent['physician_signature_type'] === 'uploaded'
                    ? 'required|image|max:2048' : 'nullable',
            ]));
        } elseif (isset($stepRules[$this->step])) {
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

        $consent = $this->consent;

        $consent['patient_signature_upload'] = null;
        $consent['witness_signature_upload'] = null;
        $consent['physician_signature_upload'] = null;

        if ($consent['patient_signature_type'] === 'uploaded' && $this->consent_upload_patient) {
            $consent['patient_signature_upload'] = $this->consent_upload_patient->store('signatures', 'public');
            $consent['patient_signature'] = null;
        }

        if ($consent['witness_signature_type'] === 'uploaded' && $this->consent_upload_witness) {
            $consent['witness_signature_upload'] = $this->consent_upload_witness->store('signatures', 'public');
            $consent['witness_signature'] = null;
        }

        if ($consent['physician_signature_type'] === 'uploaded' && $this->consent_upload_physician) {
            $consent['physician_signature_upload'] = $this->consent_upload_physician->store('signatures', 'public');
            $consent['physician_signature'] = null;
        }

        $data = [
            'patient_id' => $this->patientId,
            'category' => $this->category,
            'other_category' => $this->other_category ?: null,
            'sub_category' => $this->sub_category ?: null,
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
            'consent' => $consent,
            'take_home_medication' => $this->take_home_medication ?: null,
            'vitals' => array_filter($this->vitals, fn($v) => !is_null($v)),
            'medications' => array_filter($this->medications, fn($m) => !empty($m['drug_name'])),
            'lab_tests' => array_filter($this->labTests, fn($l) => !empty($l['test_type'])),
        ];

        $treatmentService->create($data);

        session()->flash('status', 'Treatment chart created successfully.');
        $this->redirect(route('treatments.index'), navigate: true);
    }

    public static function subCategoryOptions(string $category): array
    {
        return match ($category) {
            'checkup' => [
                'annual_comprehensive' => 'Annual or comprehensive screening',
                'periodic' => 'Periodic screening',
                'employment' => 'Employment screening',
                'traveling' => 'Traveling screening',
            ],
            'treatment' => [
                'mild_ailments' => 'Mild ailments',
                'palliative_care' => 'Palliative care',
                'home_based_care' => 'Home-based care support',
                'age_related_care' => 'Age related care support',
            ],
            'enrollment_treatment_management' => [
                'hypertension' => 'Hypertension',
                'diabetes' => 'Diabetes',
                'hypertension_diabetes' => 'Hypertension & Diabetes',
                'therapeutic_care_asthma' => 'Therapeutic care / Asthma management',
            ],
            default => [],
        };
    }

    public function render()
    {
        return view('livewire.treatment-form', [
            'patients' => Patient::with('file')->where('is_active', true)->orderBy('name')->get(),
            'staff' => \App\Models\User::where('is_active', true)
                ->whereIn('role', ['doctor', 'nurse', 'matron', 'super_admin'])
                ->orderBy('name')
                ->get(['id', 'name', 'role']),
        ]);
    }
}
