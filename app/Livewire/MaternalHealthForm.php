<?php

namespace App\Livewire;

use App\Models\MaternalHealthRecord;
use App\Models\Patient;
use App\Models\TreatmentChart;
use App\Services\TreatmentService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class MaternalHealthForm extends Component
{
    use WithFileUploads;

    // State
    public ?int $recordId = null;
    public ?int $treatmentChartId = null;
    public bool $isDraft = false;
    public int $step = 1;
    public ?int $patientId = null;
    public string $sub_option = '';

    // Step 1: Patient (read-only, loaded from DB)
    public ?Patient $patient = null;

    // Step 2: Pregnancy dating + Obstetric history
    public ?string $lmp = '';
    public string $cycle_regularity = '';
    public ?string $edd = '';
    public ?int $cga_weeks = null;
    public ?int $cga_days = null;
    public array $current_symptoms = [];
    public string $medications_exposures = '';
    public string $gravida = '';
    public string $term = '';
    public string $preterm = '';
    public string $abortion = '';
    public string $living = '';
    public array $prior_pregnancies = [];
    public string $prior_csection = '';
    public string $prior_csection_details = '';

    // Step 3: Medical + Family + Social history
    public array $chronic_conditions = [];
    public string $chronic_conditions_details = '';
    public array $infectious_disease_history = [];
    public string $prior_surgeries = '';
    public string $allergies = '';
    public string $current_medications = '';
    public array $family_genetic_history = [];
    public string $family_history_notes = '';
    public string $tobacco_vape = '';
    public ?int $tobacco_packs_per_day = null;
    public string $alcohol = '';
    public ?int $alcohol_drinks_per_week = null;
    public string $recreational_drugs = '';
    public string $recreational_drugs_details = '';
    public string $support_system = '';
    public string $safety_screening = '';
    public string $financial_stability = '';
    public string $intimate_partner_violence = '';
    public string $ipv_details = '';
    public string $occupation_hazard = '';
    public string $travel_history = '';
    public array $diet_intake = [];
    public array $physical_activities = [];

    // Step 4: Vitals + Anthropometry + RME
    public ?float $temperature = null;
    public string $temperature_unit = 'celsius';
    public ?int $pulse_bpm = null;
    public ?int $respiration_bpm = null;
    public ?int $bp_systolic = null;
    public ?int $bp_diastolic = null;
    public string $vitals_comment = '';
    public ?float $weight = null;
    public ?float $height = null;
    public ?float $bmi = null;
    public string $anthropometric_comment = '';
    public ?float $rme_fbs = null;
    public ?float $rme_rbs = null;
    public ?float $rme_pcv = null;
    public string $rme_rdta = '';
    public string $rme_glucose = '';
    public string $rme_protein = '';
    public string $rme_leukocytes = '';
    public string $rme_other_specify = '';
    public string $rme_other_result = '';
    public string $rme_comment = '';

    // Step 5: Physical exam + Obstetric exam
    public array $cardio_resp = [];
    public string $cardio_resp_comment = '';
    public array $thyroid = [];
    public string $thyroid_comment = '';
    public array $breast = [];
    public string $breast_comment = '';
    public array $extremities = [];
    public string $extremities_comment = '';
    public ?float $fundal_height_cm = null;
    public string $fetal_lie = '';
    public string $fetal_presentation = '';
    public string $fetal_position = '';
    public string $fetal_engagement = '';
    public ?int $fetal_heart_rate_bpm = null;
    public string $pelvic_vaginal_findings = '';

    // Step 6: Investigation + Diagnosis + Treatment plan
    public array $lab_tests = [];
    public string $lab_investigation_comment = '';
    public string $clinical_judgement_diagnosis = '';
    public array $medications = [];

    // Step 7: Consent + Billing
    public bool $consent_enabled = false;
    public bool $referral_letter = false;
    public ?string $next_visit_date = '';
    public string $attending_physician_name = '';
    public string $attending_physician_signature = '';
    public ?string $attending_physician_date = '';
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

    public array $stepLabels = [
        1 => 'Patient Summary',
        2 => 'Pregnancy & Obstetric History',
        3 => 'Medical & Social History',
        4 => 'Vitals & RME',
        5 => 'Physical & Obstetric Exam',
        6 => 'Investigation & Treatment',
        7 => 'Consent & Billing',
    ];

    // Route/form options
    public static array $currentSymptomOptions = [
        'none' => 'None',
        'nausea_vomiting' => 'Nausea/Vomiting',
        'vaginal_bleeding' => 'Vaginal Bleeding/Spotting',
        'abdominal_pain' => 'Abdominal Pain/Cramping',
        'pelvic_discharge' => 'Pelvic Discharge',
    ];

    public static array $chronicConditionOptions = [
        'hypertension' => 'Hypertension',
        'diabetes' => 'Diabetes (Type 1 or 2)',
        'asthma' => 'Asthma / Respiratory',
        'epilepsy' => 'Epilepsy / Seizures',
        'cardiac' => 'Cardiac Disease',
        'thromboembolism' => 'Thromboembolism/Clots',
        'mental_health' => 'Mental Health (Depression/Anxiety)',
        'thyroid' => 'Thyroid Disorder',
    ];

    public static array $infectiousDiseaseOptions = [
        'sti' => 'STI (Chlamydia/Gonorrhea)',
        'syphilis' => 'Syphilis',
        'hiv' => 'HIV',
        'hepatitis' => 'Hepatitis B/C',
        'tb' => 'Tuberculosis',
    ];

    public static array $familyGeneticOptions = [
        'genetic_errors' => 'Genetic / Inborn Errors (Cystic Fibrosis, Sickle Cell Disease/Trait, Thalassemia)',
        'heart_defects' => 'Congenital Heart Defects / Birth Defects',
        'family_history_conditions' => 'Family History of Preeclampsia, Gestational Diabetes, or Twins',
    ];

    public static array $dietIntakeOptions = [
        'general' => 'General Dietary Habit',
        'restriction' => 'Nutritional Restriction',
        'adequate' => 'Adequate Dietary Intake',
        'balanced' => 'Balance Dieting',
    ];

    public static array $physicalActivityOptions = [
        'sedentary' => 'Sedentary Habit',
        'frequent' => 'Frequent',
        'intensity' => 'Intensity',
    ];

    public static array $cardioRespOptions = [
        'lub_dub' => 'lub-dub',
        'murmur' => 'murmur/swishing',
        'arrhythmia' => 'arrhythmia/irregular',
        'crackles' => 'crackles/fluid sound',
    ];

    public static array $thyroidOptions = [
        'tenderness' => 'Tenderness',
        'masses' => 'Abnormal Masses',
        'pain' => 'Pain',
        'inflammation' => 'Inflammation',
        'ascites' => 'Ascites/Fluid Retain',
    ];

    public static array $breastOptions = [
        'structural_change' => 'Structural Change',
        'inverted_nipple' => 'Inverted Nipple',
        'masses' => 'Abnormal Masses',
        'pain' => 'Pain',
    ];

    public static array $extremitiesOptions = [
        'edema' => 'Edema (Swelling)',
        'dvt' => 'Deep Vein Thrombosis (DVT)',
        'risk_signs' => 'Risk Signs',
    ];

    public static array $routeCategories = [
        'oral' => 'Oral',
        'parenteral' => 'Parenteral (Injection)',
        'buccal' => 'Buccal (Lozenges)',
        'sublingual' => 'Sublingual',
        'inhalation' => 'Inhalation',
        'topical' => 'Topical',
        'rectal' => 'Rectal',
        'vaginal' => 'Vaginal',
        'drop' => 'Drop',
    ];

    public static array $regimeOptions = [
        'dly' => 'Daily (dly)',
        'bd' => 'bd (12hrs)',
        'tds' => 'tds (8hrs)',
        'qds' => 'qds (6hrs)',
        'nocte' => 'Nocte (night/bed)',
        'stat' => 'Stat (once)',
        'prn' => 'PRN (when needed)',
    ];

    // ─── Lifecycle ──────────────────────────────────────────────────

    public function mount(?int $patientId = null, ?string $subOption = null, ?int $recordId = null): void
    {
        $user = Auth::user();
        $this->attending_physician_name = $user?->name ?? '';

        if ($recordId) {
            $this->loadRecord($recordId);
            return;
        }

        if ($patientId) {
            $this->patientId = $patientId;
            $this->patient = Patient::with('file')->find($patientId);
            $this->sub_option = $subOption ?? 'antenatal_care';
        }

        // Initialize prior pregnancies with 4 empty rows
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
    }

    public function render()
    {
        return view('livewire.maternal-health-form', [
            'staff' => \App\Models\User::where('is_active', true)
                ->whereIn('role', ['doctor', 'nurse', 'matron', 'super_admin'])
                ->orderBy('name')
                ->get(['id', 'name', 'role']),
        ]);
    }

    // ─── Draft Management ──────────────────────────────────────────

    private function createDraft(): void
    {
        $service = app(TreatmentService::class);
        $draft = $service->createDraft(
            $this->patientId,
            'maternal_health',
            $this->sub_option,
            Auth::id()
        );

        $this->treatmentChartId = $draft->id;
        $this->isDraft = true;

        $this->recordId = MaternalHealthRecord::create([
            'treatment_chart_id' => $draft->id,
            'patient_id' => $this->patientId,
            'created_by' => Auth::id(),
        ])->id;
    }

    private function saveDraft(): void
    {
        if (!$this->recordId) return;

        $record = MaternalHealthRecord::find($this->recordId);
        if (!$record) return;

        $data = $this->buildStepData($this->step);
        $record->update($data);

        // Also save current step on the treatment chart
        if ($this->treatmentChartId) {
            TreatmentChart::where('id', $this->treatmentChartId)->update(['current_step' => $this->step + 10]);
        }
    }

    protected function buildStepData(int $step): array
    {
        return match ($step) {
            2 => [
                'lmp' => $this->lmp ?: null,
                'cycle_regularity' => $this->cycle_regularity ?: null,
                'edd' => $this->edd ?: null,
                'cga_weeks' => $this->cga_weeks,
                'cga_days' => $this->cga_days,
                'current_symptoms' => $this->current_symptoms,
                'medications_exposures' => $this->medications_exposures ?: null,
                'gravida' => $this->gravida ?: null,
                'term' => $this->term ?: null,
                'preterm' => $this->preterm ?: null,
                'abortion' => $this->abortion ?: null,
                'living' => $this->living ?: null,
                'prior_pregnancies' => $this->prior_pregnancies,
                'prior_csection' => $this->prior_csection ?: null,
                'prior_csection_details' => $this->prior_csection_details ?: null,
            ],
            3 => [
                'chronic_conditions' => $this->chronic_conditions,
                'chronic_conditions_details' => $this->chronic_conditions_details ?: null,
                'infectious_disease_history' => $this->infectious_disease_history,
                'prior_surgeries' => $this->prior_surgeries ?: null,
                'allergies' => $this->allergies ?: null,
                'current_medications' => $this->current_medications ?: null,
                'family_genetic_history' => $this->family_genetic_history,
                'family_history_notes' => $this->family_history_notes ?: null,
                'tobacco_vape' => $this->tobacco_vape ?: null,
                'tobacco_packs_per_day' => $this->tobacco_packs_per_day,
                'alcohol' => $this->alcohol ?: null,
                'alcohol_drinks_per_week' => $this->alcohol_drinks_per_week,
                'recreational_drugs' => $this->recreational_drugs ?: null,
                'recreational_drugs_details' => $this->recreational_drugs_details ?: null,
                'support_system' => $this->support_system ?: null,
                'safety_screening' => $this->safety_screening ?: null,
                'financial_stability' => $this->financial_stability ?: null,
                'intimate_partner_violence' => $this->intimate_partner_violence ?: null,
                'ipv_details' => $this->ipv_details ?: null,
                'occupation_hazard' => $this->occupation_hazard ?: null,
                'travel_history' => $this->travel_history ?: null,
                'diet_intake' => $this->diet_intake,
                'physical_activities' => $this->physical_activities,
            ],
            4 => [
                'temperature' => $this->temperature,
                'temperature_unit' => $this->temperature_unit,
                'pulse_bpm' => $this->pulse_bpm,
                'respiration_bpm' => $this->respiration_bpm,
                'bp_systolic' => $this->bp_systolic,
                'bp_diastolic' => $this->bp_diastolic,
                'vitals_comment' => $this->vitals_comment ?: null,
                'weight' => $this->weight,
                'height' => $this->height,
                'bmi' => $this->bmi,
                'anthropometric_comment' => $this->anthropometric_comment ?: null,
                'rme_fbs' => $this->rme_fbs,
                'rme_rbs' => $this->rme_rbs,
                'rme_pcv' => $this->rme_pcv,
                'rme_rdta' => $this->rme_rdta ?: null,
                'rme_glucose' => $this->rme_glucose ?: null,
                'rme_protein' => $this->rme_protein ?: null,
                'rme_leukocytes' => $this->rme_leukocytes ?: null,
                'rme_other_specify' => $this->rme_other_specify ?: null,
                'rme_other_result' => $this->rme_other_result ?: null,
                'rme_comment' => $this->rme_comment ?: null,
            ],
            5 => [
                'cardio_resp' => $this->cardio_resp,
                'cardio_resp_comment' => $this->cardio_resp_comment ?: null,
                'thyroid' => $this->thyroid,
                'thyroid_comment' => $this->thyroid_comment ?: null,
                'breast' => $this->breast,
                'breast_comment' => $this->breast_comment ?: null,
                'extremities' => $this->extremities,
                'extremities_comment' => $this->extremities_comment ?: null,
                'fundal_height_cm' => $this->fundal_height_cm,
                'fetal_lie' => $this->fetal_lie ?: null,
                'fetal_presentation' => $this->fetal_presentation ?: null,
                'fetal_position' => $this->fetal_position ?: null,
                'fetal_engagement' => $this->fetal_engagement ?: null,
                'fetal_heart_rate_bpm' => $this->fetal_heart_rate_bpm,
                'pelvic_vaginal_findings' => $this->pelvic_vaginal_findings ?: null,
            ],
            6 => [
                'lab_tests' => $this->buildLabTestData(),
                'lab_investigation_comment' => $this->lab_investigation_comment ?: null,
                'clinical_judgement_diagnosis' => $this->clinical_judgement_diagnosis ?: null,
                'medications' => $this->buildMedicationData(),
            ],
            7 => [
                'consent_enabled' => $this->consent_enabled,
                'referral_letter' => $this->referral_letter,
                'next_visit_date' => $this->next_visit_date ?: null,
                'attending_physician_name' => $this->attending_physician_name ?: null,
                'attending_physician_signature' => $this->attending_physician_signature ?: null,
                'attending_physician_date' => $this->attending_physician_date ?: null,
                'medical_bill' => $this->medical_bill,
                'bill_paid' => $this->bill_paid,
                'bill_outstanding' => $this->bill_outstanding,
            ],
            default => [],
        };
    }

    protected function buildLabTestData(): array
    {
        return array_values(array_filter($this->lab_tests, fn($t) => !empty($t['name'])));
    }

    protected function buildMedicationData(): array
    {
        return array_values(array_filter($this->medications, fn($m) => !empty($m['drug_name'])));
    }

    public function autoFillMedicalBill(): void
    {
        $this->medical_bill['laboratory_test'] = collect($this->buildLabTestData())->sum('amount');
        $this->medical_bill['medical_service'] = collect($this->buildMedicationData())->sum('amount');
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

    // ─── Dynamic Rows ──────────────────────────────────────────────

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

    // ─── BMI Auto-calc ─────────────────────────────────────────────

    public function updatedWeight(): void
    {
        $this->calculateBmi();
    }

    public function updatedHeight(): void
    {
        $this->calculateBmi();
    }

    private function calculateBmi(): void
    {
        if ($this->weight && $this->height && $this->height > 0) {
            $this->bmi = round($this->weight / (($this->height / 100) ** 2), 1);
        }
    }

    // ─── Step Navigation ───────────────────────────────────────────

    public function startForm(): void
    {
        $this->createDraft();
        $this->step = 2;
    }

    public function nextStep(): void
    {
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

    // ─── Publish ───────────────────────────────────────────────────

    private function publish(): void
    {
        $this->saveDraft();

        if ($this->treatmentChartId) {
            $chart = TreatmentChart::find($this->treatmentChartId);
            if ($chart) {
                $service = app(TreatmentService::class);
                $service->publishDraft($chart);
            }
        }

        session()->flash('status', $this->recordId ? 'Maternal health record updated successfully.' : 'Maternal health record created successfully.');
        $this->redirect(route('treatments.show', $this->treatmentChartId), navigate: true);
    }

    // ─── Load existing record ──────────────────────────────────────

    private function loadRecord(int $recordId): void
    {
        $record = MaternalHealthRecord::findOrFail($recordId);
        $this->recordId = $record->id;
        $this->treatmentChartId = $record->treatment_chart_id;
        $this->patientId = $record->patient_id;
        $this->patient = Patient::with('file')->find($record->patient_id);
        $this->sub_option = $record->treatmentChart->sub_category ?? '';
        $this->isDraft = true;

        // Load all fields from record
        foreach ($record->getAttributes() as $key => $value) {
            if (property_exists($this, $key) && !in_array($key, ['recordId', 'treatmentChartId', 'patientId', 'patient', 'sub_option', 'isDraft', 'step'])) {
                $this->$key = $record->$key ?? $this->$key;
            }
        }

        $this->step = 1;
    }

    // ─── Route forms helper ────────────────────────────────────────

    public static function routeForms(?string $category): array
    {
        return match ($category) {
            'oral' => ['tablet' => 'Tablet', 'capsule' => 'Capsule', 'syrup' => 'Syrup', 'powder' => 'Powder', 'mixture' => 'Mixture', 'emulsion' => 'Emulsion', 'linctus' => 'Linctus', 'suspension' => 'Suspension', 'solution' => 'Solution', 'drop' => 'Drop'],
            'parenteral' => ['iv' => 'I.V - Intravenous', 'im' => 'I.M - Intramuscular', 'id' => 'I.D - Intradermal', 'it' => 'I.T - Intra Thecal', 'subq' => 'SubQ - Subcutaneous', 'ip' => 'I.P - Intraperitonial'],
            'buccal' => ['candied' => 'Candied', 'pastilles' => 'Pastilles (Gumes)', 'troches' => 'Troches', 'elixirs' => 'Elixirs (Sweet)'],
            'sublingual' => ['tablet' => 'Tablet', 'films' => 'Films', 'spray' => 'Spray', 'drops' => 'Drops'],
            'inhalation' => ['pmdi' => 'Pressurized Metered Dose Inhalers', 'dpi' => 'Dry Power Inhalers', 'smi' => 'Soft Mist Inhalers', 'mobilizers' => 'Mobilizers', 'low_flow' => 'Low Flow Devices'],
            'topical' => ['cream' => 'Cream', 'ointment' => 'Ointment', 'gel' => 'Gel', 'lotion' => 'Lotion', 'liniment' => 'Liniment', 'collodion' => 'Collodion', 'patches' => 'Patches', 'powder' => 'Powder'],
            'rectal' => ['cream' => 'Cream', 'enemas' => 'Enemas', 'suppository' => 'Suppository', 'ointment' => 'Ointment', 'pessary' => 'Pessary'],
            'vaginal' => ['cream' => 'Cream', 'suppository' => 'Suppository', 'ointment' => 'Ointment', 'pessary' => 'Pessary'],
            'drop' => ['drop' => 'Drop'],
            default => [],
        };
    }

    public static function strengthOptions(): array
    {
        return ['ml' => 'ML', 'cc' => 'CC', 'mg' => 'MG', 'g' => 'G'];
    }

    public static function lengthUnitOptions(): array
    {
        return ['days' => 'Days', 'weeks' => 'Weeks', 'months' => 'Months'];
    }
}
