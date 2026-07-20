<?php

namespace App\Livewire\TreatmentComponents\MaternalHealth;

trait WithMaternalVitalsExam
{
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

    public array $ipt_medications = [];

    public array $immunization_medications = [];

    // Step 7: Consent + Billing
    public bool $consent_enabled = false;

    public bool $referral_letter = false;

    public string $attending_physician_name = '';

    public string $attending_physician_signature = '';

    public string $attending_physician_signature_type = 'typed';

    public $attending_physician_signature_upload = null;

    public ?string $attending_physician_date = '';

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
}
