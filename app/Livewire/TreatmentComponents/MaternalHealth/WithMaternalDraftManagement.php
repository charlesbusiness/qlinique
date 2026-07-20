<?php

namespace App\Livewire\TreatmentComponents\MaternalHealth;

use App\Livewire\TreatmentComponents\Concerns\HasBase64Signature;
use App\Models\MaternalHealthRecord;
use App\Models\Patient;
use App\Models\TreatmentChart;
use App\Services\TreatmentService;
use Illuminate\Support\Facades\Auth;

/**
 * @property-write int $step
 * @property-write ?int $patientId
 * @property-write string $sub_option
 * @property-write ?int $treatmentChartId
 * @property-write bool $isDraft
 * @property-write ?int $recordId
 * @property-write ?Patient $patient
 * @property-write ?string $lmp
 * @property-write string $cycle_regularity
 * @property-write ?string $edd
 * @property-write ?int $cga_weeks
 * @property-write ?int $cga_days
 * @property-write array $current_symptoms
 * @property-write string $medications_exposures
 * @property-write string $gravida
 * @property-write string $term
 * @property-write string $preterm
 * @property-write string $abortion
 * @property-write string $living
 * @property-write array $prior_pregnancies
 * @property-write string $prior_csection
 * @property-write string $prior_csection_details
 * @property-write array $chronic_conditions
 * @property-write string $chronic_conditions_details
 * @property-write array $infectious_disease_history
 * @property-write string $prior_surgeries
 * @property-write string $allergies
 * @property-write string $current_medications
 * @property-write array $family_genetic_history
 * @property-write string $family_history_notes
 * @property-write string $tobacco_vape
 * @property-write ?int $tobacco_packs_per_day
 * @property-write string $alcohol
 * @property-write ?int $alcohol_drinks_per_week
 * @property-write string $recreational_drugs
 * @property-write string $recreational_drugs_details
 * @property-write string $support_system
 * @property-write string $safety_screening
 * @property-write string $financial_stability
 * @property-write string $intimate_partner_violence
 * @property-write string $ipv_details
 * @property-write string $occupation_hazard
 * @property-write string $travel_history
 * @property-write array $diet_intake
 * @property-write array $physical_activities
 * @property-write ?float $temperature
 * @property-write string $temperature_unit
 * @property-write ?int $pulse_bpm
 * @property-write ?int $respiration_bpm
 * @property-write ?int $bp_systolic
 * @property-write ?int $bp_diastolic
 * @property-write ?int $oxygen_saturation
 * @property-write string $vitals_comment
 * @property-write ?float $weight
 * @property-write ?float $height
 * @property-write ?float $bmi
 * @property-write string $anthropometric_comment
 * @property-write ?float $rme_fbs
 * @property-write ?float $rme_rbs
 * @property-write ?float $rme_pcv
 * @property-write string $rme_rdta
 * @property-write string $rme_glucose
 * @property-write string $rme_protein
 * @property-write string $rme_leukocytes
 * @property-write string $rme_other_specify
 * @property-write string $rme_other_result
 * @property-write string $rme_comment
 * @property-write array $cardio_resp
 * @property-write string $cardio_resp_comment
 * @property-write array $thyroid
 * @property-write string $thyroid_comment
 * @property-write array $breast
 * @property-write string $breast_comment
 * @property-write array $extremities
 * @property-write string $extremities_comment
 * @property-write ?float $fundal_height_cm
 * @property-write string $fetal_lie
 * @property-write string $fetal_presentation
 * @property-write string $fetal_position
 * @property-write string $fetal_engagement
 * @property-write ?int $fetal_heart_rate_bpm
 * @property-write string $pelvic_vaginal_findings
 * @property-write array $lab_tests
 * @property-write string $lab_investigation_comment
 * @property-write string $clinical_judgement_diagnosis
 * @property-write array $medications
 * @property-write array $ipt_medications
 * @property-write array $immunization_medications
 * @property-write bool $consent_enabled
 * @property-write bool $referral_letter
 * @property-write string $attending_physician_name
 * @property-write string $attending_physician_signature
 * @property-write ?string $attending_physician_date
 * @property-write array $medical_bill
 * @property-write float $bill_paid
 * @property-write float $bill_outstanding
 */
trait WithMaternalDraftManagement
{
    use HasBase64Signature;
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
        if (! $this->recordId) {
            return;
        }

        $record = MaternalHealthRecord::find($this->recordId);
        if (! $record) {
            return;
        }

        $data = $this->buildStepData($this->step);
        $record->update($data);

        // Also save current step on the treatment chart
        if ($this->treatmentChartId) {
            $chartUpdates = ['current_step' => $this->step + 10];

            if ($this->step === 7) {
                $chartUpdates['medical_bill'] = $this->medical_bill;
            }

            TreatmentChart::where('id', $this->treatmentChartId)->update($chartUpdates);
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
                'oxygen_saturation' => $this->oxygen_saturation,
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
                'ipt_medications' => $this->buildIptMedicationData(),
                'immunization_medications' => $this->buildImmunizationMedicationData(),
            ],
            7 => [
                'consent_enabled' => $this->consent_enabled,
                'referral_letter' => $this->referral_letter,
                'attending_physician_name' => $this->attending_physician_name ?: null,
                'attending_physician_signature' => $this->buildPhysicianSignature(),
                'attending_physician_signature_type' => $this->attending_physician_signature_type,
                'attending_physician_date' => $this->attending_physician_date ?: null,
                'medical_bill' => $this->medical_bill,
                'bill_paid' => $this->bill_paid,
                'bill_outstanding' => $this->bill_outstanding,
            ],
            default => [],
        };
    }

    private function publish(): void
    {
        $this->saveDraft();

        if ($this->treatmentChartId) {
            $chart = TreatmentChart::find($this->treatmentChartId);
            if ($chart) {
                $service = app(TreatmentService::class);
                $service->publishDraft($chart);
            }

            // Mark the current visit as completed on revisit
            if ($this->visitType === 'revisit') {
                $record = MaternalHealthRecord::find($this->recordId);
                if ($record) {
                    // If a specific visit ID was passed, mark that one
                    if ($this->visitId) {
                        \App\Models\AntenatalVisit::where('id', $this->visitId)
                            ->where('patient_id', $this->patientId)
                            ->update(['status' => 'completed', 'completed_at' => now()]);
                    } else {
                        // Fallback: mark by visit number
                        $currentVisitNum = $this->doseNumber;
                        $record->antenatalVisits()
                            ->where('visit_number', $currentVisitNum)
                            ->update(['status' => 'completed', 'completed_at' => now()]);
                    }
                }
            }
        }

        session()->flash('status', $this->recordId ? 'Maternal health record updated successfully.' : 'Maternal health record created successfully.');
        $this->redirect(route('treatments.show', $this->treatmentChartId), navigate: true);
    }

    private function loadRecord(int $recordId): void
    {
        $record = MaternalHealthRecord::with('antenatalVisits')->findOrFail($recordId);
        $this->recordId = $record->id;
        $this->treatmentChartId = $record->treatment_chart_id;
        $this->patientId = $record->patient_id;
        $this->patient = Patient::with('file')->find($record->patient_id);
        $this->sub_option = $record->treatmentChart->sub_category ?? '';
        $this->isDraft = true;
        $this->isEditing = true;

        // Determine visit type for edit: first_contact if no prior records, otherwise revisit
        $priorRecords = MaternalHealthRecord::where('patient_id', $this->patientId)
            ->where('id', '<', $this->recordId)
            ->count();
        $this->visitType = $priorRecords === 0 ? 'first_contact' : 'revisit';

        // Load all fields from record
        foreach ($record->getAttributes() as $key => $value) {
            if (property_exists($this, $key) && ! in_array($key, ['recordId', 'treatmentChartId', 'patientId', 'patient', 'sub_option', 'isDraft', 'step', 'isEditing', 'visitType'])) {
                $this->$key = $record->$key ?? $this->$key;
            }
        }

        $this->step = 1;
    }

    private function buildPhysicianSignature(): ?string
    {
        if ($this->attending_physician_signature_type === 'drawn' && $this->attending_physician_signature) {
            return $this->base64ToUploadedFile($this->attending_physician_signature)->store('signatures', 'public');
        }

        if ($this->attending_physician_signature_type === 'uploaded' && $this->attending_physician_signature_upload) {
            return $this->attending_physician_signature_upload->store('signatures', 'public');
        }

        return $this->attending_physician_signature ?: null;
    }
}
