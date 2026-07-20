<?php

namespace App\Livewire;

use App\Livewire\TreatmentComponents\Treatment\WithAntenatalRegistration;
use App\Livewire\TreatmentComponents\Treatment\WithBilling;
use App\Livewire\TreatmentComponents\Treatment\WithConstants;
use App\Livewire\TreatmentComponents\Treatment\WithDraftManagement;
use App\Livewire\TreatmentComponents\Treatment\WithDynamicRows;
use App\Livewire\TreatmentComponents\Treatment\WithMaternalHealthFlow;
use App\Livewire\TreatmentComponents\Treatment\WithStepNavigation;
use App\Models\Patient;
use App\Models\TreatmentChart;
use App\Models\User;
use App\Services\TreatmentService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class TreatmentForm extends Component
{
    use WithAntenatalRegistration;
    use WithBilling;
    use WithConstants;
    use WithDraftManagement;
    use WithDynamicRows;
    use WithFileUploads;
    use WithMaternalHealthFlow;
    use WithStepNavigation;

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

    public $consent_drawn_patient = null;

    public $consent_drawn_witness = null;

    public $consent_drawn_physician = null;

    // ─── Lifecycle ──────────────────────────────────────────────────

    public function mount(?int $patientId = null, ?int $treatmentId = null): void
    {
        $user = Auth::user();

        // Reset maternal flow state
        $this->resetMaternalState();
        $this->registrationSuccess = false;
        $this->registeredPatientName = '';

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
            'antenatalPatients' => Patient::with('file')
                ->where('is_active', true)
                ->where('patient_type', 'antenatal')
                ->orderBy('name')
                ->get(),
            'staff' => User::where('is_active', true)
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
        if (! array_key_exists($category, self::implementedCategories())) {
            return;
        }

        $this->selectedCategory = $category;
        $this->selectedSubOption = '';

        // Maternal health: show sub-categories (no patient selection needed)
        if ($category === 'maternal_health') {
            $this->maternalFlow = 'sub_categories';

            return;
        }

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
        $this->resetMaternalState();
        $this->registrationSuccess = false;
        $this->registeredPatientName = '';
    }

    public function selectCategory(): void
    {
        if (! $this->selectedCategory) {
            return;
        }

        $this->createDraft();
    }

    public function resetToCategorySelection(): void
    {
        $this->selectedCategory = '';
        $this->selectedSubOption = '';
        $this->patientId = null;
        $this->registrationSuccess = false;
        $this->registeredPatientName = '';
        $this->resetMaternalState();
        $this->resetRegistrationFields();
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
}
