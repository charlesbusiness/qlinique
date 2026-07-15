<?php

namespace App\Livewire;

use App\Livewire\Concerns\WithBilling;
use App\Livewire\Concerns\WithConstants;
use App\Livewire\Concerns\WithDraftManagement;
use App\Livewire\Concerns\WithDynamicRows;
use App\Models\Patient;
use App\Models\TreatmentChart;
use App\Services\TreatmentService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class TreatmentForm extends Component
{
    use WithFileUploads;
    use WithConstants;
    use WithDraftManagement;
    use WithDynamicRows;
    use WithBilling;

    // Draft state
    public ?int $draftId = null;
    public bool $isDraft = false;
    public ?int $treatmentId = null;
    public bool $isEditing = false;
    public int $step = 0;
    public bool $showCategory = false;
    public bool $showStep1 = false;

    // Assessment selection
    public string $selectedCategory = '';
    public string $selectedSubOption = '';

    // Step 1: Patient & History
    public ?int $patientId = null;
    public string $sub_category = '';
    public string $finding_on_history = '';
    public string $previous_treatment_history = '';
    public string $recommended_drugs = '';
    public string $allergies = '';

    // Step 2: Vital Signs
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
        'comment' => '',
    ];

    // Step 3 & 4: Physical Examination
    public string $anthropometryComment = '';
    public array $heartLungsFindings = [];
    public string $heartLungsComment = '';
    public array $eentEyesFindings = [];
    public array $eentEarsFindings = [];
    public array $eentNoseFindings = [];
    public array $eentThroatFindings = [];
    public string $eentComment = '';
    public array $abdominalFindings = [];
    public string $abdominalComment = '';
    public string $reflexFinding = '';
    public string $reflexComment = '';
    public array $hairFindings = [];
    public string $hairComment = '';
    public array $skinFindings = [];
    public string $skinComment = '';

    // Step 5: Investigation & Diagnosis
    public array $rmeResults = [];
    public string $rmeComment = '';
    public string $rmeNewTest = '';
    public array $labTests = [];
    public array $labTestUploads = [];
    public string $primary_diagnosis = '';

    // Step 6: Treatment Plan & Billing
    public array $treatmentPlanItems = [];
    public bool $consent_enabled = false;
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

    public array $medicalBill = [
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
    public float $billTotal = 0;
    public float $billPaid = 0;
    public float $billOutstanding = 0;
    public float $previousOutstanding = 0;

    public array $stepLabels = [
        1 => 'Patient & History',
        2 => 'Vital Signs',
        3 => 'Physical Exam 1',
        4 => 'Physical Exam 2',
        5 => 'Investigation & Diagnosis',
        6 => 'Treatment Plan',
        7 => 'Billing',
    ];

    // ─── Lifecycle ──────────────────────────────────────────────────

    public function mount(?int $patientId = null, ?int $treatmentId = null): void
    {
        $user = Auth::user();

        if ($treatmentId) {
            $treatment = TreatmentChart::with('vitals', 'physicalExaminations', 'rmeResults', 'labTests', 'treatmentPlanItems')->findOrFail($treatmentId);
            $this->treatmentId = $treatmentId;
            $this->isEditing = true;
            $this->loadDraft($treatment);
            $this->isDraft = false;
            $this->showCategory = true;
            $this->showStep1 = true;
            $this->step = 1;
            $this->consent['attending_physician'] = $user?->name ?? '';
            return;
        }

        if ($patientId) {
            $this->patientId = $patientId;
            $this->showCategory = true;
        }

        $this->consent['attending_physician'] = $user?->name ?? '';
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

    // ─── Assessment Selection ────────────────────────────────────────

    public function selectPatient(): void
    {
        if ($this->patientId) {
            $this->showCategory = true;
            $this->step = 1;
        }
    }

    public function selectAssessmentCategory(string $category): void
    {
        if (!array_key_exists($category, self::implementedCategories())) {
            return;
        }

        $this->selectedCategory = $category;
        $this->selectedSubOption = '';

        $subOptions = self::assessmentSubOptions($category);
        if (empty($subOptions)) {
            $this->sub_category = $category;
            $this->createDraft();
        }
    }

    public function selectAssessmentSubOption(string $subOption): void
    {
        $this->selectedSubOption = $subOption;
        $this->sub_category = $subOption;
        $this->createDraft();
    }

    public function goBackToCategories(): void
    {
        $this->selectedCategory = '';
        $this->selectedSubOption = '';
    }

    public function selectCategory(): void
    {
        if (!$this->selectedCategory) return;

        if ($this->selectedCategory === 'maternal_health') {
            $this->redirect(route('treatments.maternal.create', [
                'patient_id' => $this->patientId,
                'sub_option' => $this->sub_category,
            ]), navigate: true);
            return;
        }

        $this->createDraft();
    }

    private function createDraft(): void
    {
        $service = app(TreatmentService::class);
        $draft = $service->createDraft(
            $this->patientId,
            $this->selectedCategory,
            $this->sub_category,
            Auth::id()
        );

        $this->draftId = $draft->id;
        $this->isDraft = true;
        $this->showStep1 = true;
        $this->step = 1;
    }

    // ─── Temperature Validation ────────────────────────────────────

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

    // ─── Step Navigation ───────────────────────────────────────────

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
                'consent.patient_signature_type' => 'required_if:consent_enabled,true|in:typed,uploaded',
                'consent.patient_signature' => 'required_if:consent.patient_signature_type,typed|string|max:255',
                'consent.witness_name' => 'required_if:consent_enabled,true|string|max:255',
                'consent.witness_signature_type' => 'required_if:consent_enabled,true|in:typed,uploaded',
                'consent.witness_signature' => 'required_if:consent.witness_signature_type,typed|string|max:255',
                'consent.physician_signature_type' => 'required_if:consent_enabled,true|in:typed,uploaded',
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
        if (isset($rules[$this->step]) && !empty($rules[$this->step])) {
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
}
