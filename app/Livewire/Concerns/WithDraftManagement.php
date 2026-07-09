<?php

namespace App\Livewire\Concerns;

use App\Models\TreatmentChart;
use App\Services\TreatmentService;

trait WithDraftManagement
{
    public function saveDraft(): void
    {
        if (!$this->draftId) return;

        $service = app(TreatmentService::class);
        $draft = TreatmentChart::find($this->draftId);
        if (!$draft) return;

        $data = $this->buildStepData($this->step);
        $service->saveStep($draft, $this->step, $data);
    }

    protected function buildStepData(int $step): array
    {
        return match ($step) {
            1 => [
                'patient_id' => $this->patientId,
                'category' => 'treatment',
                'sub_category' => $this->sub_category,
                'finding_on_history' => $this->finding_on_history ?: null,
                'previous_treatment_history' => $this->previous_treatment_history ?: null,
                'recommended_drugs' => $this->recommended_drugs ?: null,
                'allergies' => $this->allergies ?: null,
            ],
            2 => [
                'vitals' => array_filter($this->vitals, fn($v) => $v !== null && $v !== ''),
            ],
            3 => [
                'physical_examinations' => $this->buildPhysicalExamData(),
                'vitals' => array_filter($this->vitals, fn($v) => $v !== null && $v !== ''),
            ],
            4 => [
                'physical_examinations' => $this->buildPhysicalExamData(),
            ],
            5 => [
                'rme_results' => $this->rmeResults,
                'rme_comment' => $this->rmeComment ?: null,
                'lab_tests' => $this->buildLabTestData(),
                'primary_diagnosis' => $this->primary_diagnosis ?: null,
            ],
            6 => [
                'treatment_plan_items' => $this->buildTreatmentPlanData(),
                'consent_enabled' => $this->consent_enabled,
                'consent' => $this->buildConsentData(),
            ],
            7 => [
                'medical_bill' => $this->medicalBill + [
                    'total' => $this->billTotal,
                    'paid' => $this->billPaid,
                    'outstanding' => $this->billOutstanding,
                    'previous_outstanding' => $this->previousOutstanding,
                ],
            ],
            default => [],
        };
    }

    protected function buildPhysicalExamData(): array
    {
        $data = [
            ['section' => 'anthropometry', 'findings' => [], 'comment' => $this->anthropometryComment ?: null],
            ['section' => 'heart_lungs', 'findings' => $this->heartLungsFindings, 'comment' => $this->heartLungsComment ?: null],
            ['section' => 'eyes_ears_nose_throat', 'findings' => [
                'eyes' => $this->eentEyesFindings,
                'ears' => $this->eentEarsFindings,
                'nose' => $this->eentNoseFindings,
                'throat' => $this->eentThroatFindings,
            ], 'comment' => $this->eentComment ?: null],
            ['section' => 'abdominal', 'findings' => $this->abdominalFindings, 'comment' => $this->abdominalComment ?: null],
            ['section' => 'reflex', 'findings' => $this->reflexFinding ? [$this->reflexFinding] : [], 'comment' => $this->reflexComment ?: null],
            ['section' => 'hair', 'findings' => $this->hairFindings, 'comment' => $this->hairComment ?: null],
            ['section' => 'skin', 'findings' => $this->skinFindings, 'comment' => $this->skinComment ?: null],
        ];
        return array_filter($data, fn($d) => $d['findings'] || $d['comment']);
    }

    protected function buildLabTestData(): array
    {
        $tests = [];
        foreach ($this->labTests as $i => $lab) {
            if (empty($lab['test_type'])) continue;
            $item = [
                'test_type' => $lab['test_type'],
                'sample_type' => $lab['sample_type'] ?? null,
                'amount' => $lab['amount'] ?? 0,
                'findings' => $lab['findings'] ?? null,
            ];
            if (isset($this->labTestUploads[$i]) && $this->labTestUploads[$i]) {
                $item['attachment_path'] = $this->labTestUploads[$i]->store('lab-results', 'public');
            }
            $tests[] = $item;
        }
        return $tests;
    }

    protected function buildTreatmentPlanData(): array
    {
        $items = array_filter($this->treatmentPlanItems, fn($item) => !empty($item['drug_name']));

        return array_values(array_map(fn($item) => array_merge($item, [
            'length_display' => $item['length_display'] ?? $this->lengthDisplay($item['length_value'] ?? 1, $item['length_unit'] ?? 'days'),
        ]), $items));
    }

    protected function buildConsentData(): array
    {
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

        return $consent;
    }

    protected function loadDraft(TreatmentChart $draft): void
    {
        $this->draftId = $draft->id;
        $this->isDraft = true;
        $this->patientId = $draft->patient_id;
        $this->sub_category = $draft->sub_category ?? '';
        $this->step = $draft->current_step ?? 1;
        $this->showCategory = true;
        $this->showStep1 = true;

        $this->finding_on_history = $draft->finding_on_history ?? '';
        $this->previous_treatment_history = $draft->previous_treatment_history ?? '';
        $this->recommended_drugs = $draft->recommended_drugs ?? '';
        $this->allergies = $draft->allergies ?? '';
        $this->primary_diagnosis = $draft->primary_diagnosis ?? '';

        if ($draft->vitals()->exists()) {
            $vital = $draft->vitals()->first();
            $this->vitals = [
                'temperature' => $vital->temperature,
                'temperature_unit' => $vital->temperature_unit ?? 'celsius',
                'blood_pressure_systolic' => $vital->blood_pressure_systolic,
                'blood_pressure_diastolic' => $vital->blood_pressure_diastolic,
                'pulse_rate' => $vital->pulse_rate,
                'respiratory_rate' => $vital->respiratory_rate,
                'weight' => $vital->weight,
                'height' => $vital->height,
                'oxygen_saturation' => $vital->oxygen_saturation,
                'bmi' => $vital->bmi,
                'comment' => $vital->comment ?? '',
            ];
        }

        foreach ($draft->physicalExaminations as $exam) {
            $findings = $exam->findings ?? [];
            switch ($exam->section) {
                case 'anthropometry':
                    $this->anthropometryComment = $exam->comment ?? '';
                    break;
                case 'heart_lungs':
                    $this->heartLungsFindings = $findings;
                    $this->heartLungsComment = $exam->comment ?? '';
                    break;
                case 'eyes_ears_nose_throat':
                    $this->eentEyesFindings = $findings['eyes'] ?? [];
                    $this->eentEarsFindings = $findings['ears'] ?? [];
                    $this->eentNoseFindings = $findings['nose'] ?? [];
                    $this->eentThroatFindings = $findings['throat'] ?? [];
                    $this->eentComment = $exam->comment ?? '';
                    break;
                case 'abdominal':
                    $this->abdominalFindings = $findings;
                    $this->abdominalComment = $exam->comment ?? '';
                    break;
                case 'reflex':
                    $this->reflexFinding = is_array($findings) && count($findings) > 0 ? $findings[0] : '';
                    $this->reflexComment = $exam->comment ?? '';
                    break;
                case 'hair':
                    $this->hairFindings = $findings;
                    $this->hairComment = $exam->comment ?? '';
                    break;
                case 'skin':
                    $this->skinFindings = $findings;
                    $this->skinComment = $exam->comment ?? '';
                    break;
            }
        }

        $this->rmeResults = array_values($draft->rmeResults->map(fn($rme) => [
            'test_name' => $rme->test_name,
            'result' => $rme->result ?? '',
            'amount' => $rme->amount ?? 0,
        ])->all());
        $this->rmeComment = $draft->rme_comment ?? '';

        foreach ($draft->labTests as $lab) {
            $this->labTests[] = [
                'test_type' => $lab->test_type,
                'sample_type' => $lab->sample_type ?? '',
                'amount' => $lab->amount ?? 0,
                'findings' => $lab->findings ?? '',
            ];
        }

        foreach ($draft->treatmentPlanItems as $item) {
            $this->treatmentPlanItems[] = [
                'route_category' => $item->route_category,
                'route_form' => $item->route_form,
                'drug_name' => $item->drug_name,
                'strength' => $item->strength ?? '',
                'dosage' => $item->dosage ?? '',
                'regime' => $item->regime,
                'length_value' => $item->length_value,
                'length_unit' => $item->length_unit,
                'length_display' => $this->lengthDisplay($item->length_value, $item->length_unit),
                'amount' => $item->amount,
                'is_take_home' => $item->is_take_home,
            ];
        }

        $this->consent_enabled = $draft->consent_enabled ?? false;
        if ($draft->consent) {
            $this->consent = array_merge($this->consent, $draft->consent);
        }

        if ($draft->medical_bill) {
            $this->medicalBill = array_merge($this->medicalBill, array_diff_key($draft->medical_bill, array_flip(['total', 'paid', 'outstanding', 'previous_outstanding'])));
            $this->billPaid = $draft->medical_bill['paid'] ?? 0;
        }

        if ($this->step >= 6) {
            $this->autoFillMedicalBill();
        }
    }

    protected function publish(): void
    {
        $this->saveDraft();

        $service = app(TreatmentService::class);
        $chart = TreatmentChart::find($this->draftId);

        if ($this->isEditing) {
            if ($chart) {
                $chart->is_completed = false;
                $chart->save();
                $service->syncSchedule($chart);
            }
            session()->flash('status', 'Treatment chart updated successfully.');
            $this->redirect(route('treatments.show', $this->treatmentId), navigate: true);
            return;
        }

        if ($chart) {
            $service->publishDraft($chart);
        }

        session()->flash('status', 'Treatment chart created successfully.');
        $this->redirect(route('treatments.index'), navigate: true);
    }

    public function discardDraft(): void
    {
        if ($this->draftId) {
            $service = app(TreatmentService::class);
            $draft = TreatmentChart::find($this->draftId);
            if ($draft) {
                $service->discardDraft($draft);
            }
        }
        $this->redirect(route('treatments.create'), navigate: true);
    }
}
