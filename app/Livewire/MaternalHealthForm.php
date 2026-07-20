<?php

namespace App\Livewire;

use App\Livewire\TreatmentComponents\MaternalHealth\WithMaternalBilling;
use App\Livewire\TreatmentComponents\MaternalHealth\WithMaternalDraftManagement;
use App\Livewire\TreatmentComponents\MaternalHealth\WithMaternalDynamicRows;
use App\Livewire\TreatmentComponents\MaternalHealth\WithMaternalFormOptions;
use App\Livewire\TreatmentComponents\MaternalHealth\WithMaternalPregnancyHistory;
use App\Livewire\TreatmentComponents\MaternalHealth\WithMaternalVitalsExam;
use App\Models\AntenatalVisit;
use App\Models\MaternalHealthRecord;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class MaternalHealthForm extends Component
{
    use WithFileUploads;
    use WithMaternalBilling;
    use WithMaternalDraftManagement;
    use WithMaternalDynamicRows;
    use WithMaternalFormOptions;
    use WithMaternalPregnancyHistory;
    use WithMaternalVitalsExam;

    // State
    public ?int $recordId = null;

    public ?int $treatmentChartId = null;

    public bool $isDraft = false;

    public bool $isEditing = false;

    public int $step = 1;

    public int $startStep = 1;

    public ?int $patientId = null;

    public string $sub_option = '';

    public string $visitType = '';

    public string $scheduleType = '';

    public ?int $visitId = null;

    // Step 1: Patient (read-only, loaded from DB)
    public ?Patient $patient = null;

    public array $stepLabels = [
        1 => 'Patient Summary',
        2 => 'Pregnancy & Obstetric History',
        3 => 'Medical & Social History',
        4 => 'Vitals & RME',
        5 => 'Physical & Obstetric Exam',
        6 => 'Investigation & Treatment',
        7 => 'Consent & Billing',
    ];

    public function getStepLabelsProperty(): array
    {
        return [
            1 => 'Patient Summary',
            2 => 'Pregnancy & Obstetric History',
            3 => 'Medical & Social History',
            4 => 'Vitals & RME',
            5 => 'Physical & Obstetric Exam',
            6 => 'Investigation & Treatment',
            7 => 'Consent & Billing',
        ];
    }

    // ─── Lifecycle ──────────────────────────────────────────────────

    public function mount(?int $patientId = null, ?string $subOption = null, ?int $recordId = null, int $startStep = 1, string $visitType = '', ?int $visitId = null): void
    {
        $user = Auth::user();
        $this->attending_physician_name = $user?->name ?? '';
        $this->startStep = $startStep;
        $this->visitType = $visitType;
        $this->visitId = $visitId;

        if ($recordId) {
            $this->loadRecord($recordId);

            return;
        }

        if ($patientId) {
            $this->patientId = $patientId;
            $this->patient = Patient::with('file')->find($patientId);
            $this->sub_option = $subOption ?? 'antenatal_care';

            // For Re-visit: start at step 4, skip steps 1-3
            if ($startStep === 4) {
                $this->step = 4;
            }

            // Auto-start the form (create draft and advance)
            $this->startForm();
        }

        // Initialize prior pregnancies with 1 empty row
        if (empty($this->prior_pregnancies)) {
            $this->prior_pregnancies = array_fill(0, 1, [
                'year' => '', 'gest_age' => '', 'mode_of_delivery' => '',
                'birth_weight' => '', 'complications' => '', 'neonatal_outcome' => '',
            ]);
        }

        // Initialize lab tests with 3 empty rows
        if (empty($this->lab_tests)) {
            $this->lab_tests = array_fill(0, 3, [
                'name' => '', 'specimen' => '', 'amount' => 0, 'attachment' => null,
            ]);
        }

        // Initialize medications with 1 empty row
        if (empty($this->medications)) {
            $this->medications = [['route_category' => '', 'route_form' => '', 'drug_name' => '', 'strength' => '', 'dosage' => '', 'regime' => '', 'length_value' => '', 'length_unit' => 'days', 'amount' => 0, 'is_take_home' => 1]];
        }

        // Initialize IPT medications with 1 empty row
        if (empty($this->ipt_medications)) {
            $this->ipt_medications = [['route_category' => '', 'route_form' => '', 'drug_name' => '', 'strength' => '', 'dosage' => '', 'regime' => '', 'length_value' => '', 'length_unit' => 'days', 'amount' => 0]];
        }

        // Initialize Immunization medications with 1 empty row
        if (empty($this->immunization_medications)) {
            $this->immunization_medications = [['route_category' => '', 'route_form' => '', 'drug_name' => '', 'strength' => '', 'dosage' => '', 'regime' => '', 'length_value' => '', 'length_unit' => 'days', 'amount' => 0]];
        }
    }

    public function render()
    {
        return view('livewire.maternal-health-form', [
            'staff' => User::where('is_active', true)
                ->whereIn('role', ['doctor', 'nurse', 'matron', 'super_admin'])
                ->orderBy('name')
                ->get(['id', 'name', 'role']),
        ]);
    }

    public function getDoseNumberProperty(): int
    {
        if ($this->visitType === 'first_contact') {
            return 1;
        }

        // If a specific visit ID was passed (from list page revisit action), use its visit number
        if ($this->visitId) {
            $visit = \App\Models\AntenatalVisit::find($this->visitId);
            if ($visit) {
                return $visit->visit_number;
            }
        }

        // For revisit: find the next scheduled visit for this patient
        $nextVisit = AntenatalVisit::where('patient_id', $this->patientId)
            ->where('status', 'scheduled')
            ->orderBy('visit_number')
            ->first();

        if ($nextVisit) {
            return $nextVisit->visit_number;
        }

        // Fallback: count completed visits + 1
        return AntenatalVisit::where('patient_id', $this->patientId)
            ->where('status', 'completed')
            ->count() + 1;
    }

    // ─── Step Navigation ───────────────────────────────────────────

    public function startForm(): void
    {
        $this->createDraft();
        $this->step = $this->startStep;
    }

    public function stepRules(): array
    {
        return [];
    }

    public function validationAttributes(): array
    {
        return [];
    }

    public function nextStep(): void
    {
        $rules = $this->stepRules();

        if (! empty($rules)) {
            $this->validate($rules, [], $this->validationAttributes());
        }

        $this->saveDraft();

        if ($this->step === 6) {
            $this->autoFillMedicalBill();
        }

        if ($this->step === 7) {
            $this->publish();

            return;
        }

        $this->step++;
    }

    public function prevStep(): void
    {
        if ($this->step > 1) {
            $this->step--;
        }
    }
}
