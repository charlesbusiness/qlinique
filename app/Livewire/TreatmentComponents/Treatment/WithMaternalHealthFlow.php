<?php

namespace App\Livewire\TreatmentComponents\Treatment;

trait WithMaternalHealthFlow
{
    // Maternal health flow
    public string $maternalFlow = '';

    public string $maternalSubCategory = '';

    public string $antenatalOption = '';

    // Maternal health patient selection
    public string $maternalPatientSelectionType = '';

    public ?int $antenatalPatientId = null;

    public ?int $revisitPatientId = null;

    public string $selectedAntenatalVisitType = '';

    public function selectMaternalSubCategory(string $subCategory): void
    {
        $this->maternalSubCategory = $subCategory;

        if ($subCategory === 'antenatal_care') {
            $this->maternalFlow = 'antenatal_options';
        }
    }

    public function goBackToMaternalSubCategories(): void
    {
        $this->maternalFlow = 'sub_categories';
        $this->maternalSubCategory = '';
        $this->antenatalOption = '';
    }

    public function selectAntenatalOption(string $option): void
    {
        $this->antenatalOption = $option;

        if ($option === 'registration') {
            $this->maternalFlow = 'registration';
        }
    }

    public function goBackToAntenatalOptions(): void
    {
        $this->maternalFlow = 'antenatal_options';
        $this->antenatalOption = '';
        $this->antenatalPatientId = null;
        $this->revisitPatientId = null;
        $this->maternalPatientSelectionType = '';
        $this->selectedAntenatalVisitType = '';
    }

    public function selectAntenatalFirstContact(): void
    {
        $this->maternalFlow = 'antenatal_patient_selection';
        $this->maternalPatientSelectionType = 'first_contact';
        $this->selectedAntenatalVisitType = 'first_contact';
    }

    public function selectRevisit(): void
    {
        $this->maternalFlow = 'revisit_patient_selection';
        $this->maternalPatientSelectionType = 'revisit';
        $this->selectedAntenatalVisitType = 'revisit';
    }

    public function selectUnscheduledVisit(): void
    {
        $this->maternalFlow = 'revisit_patient_selection';
        $this->maternalPatientSelectionType = 'revisit';
        $this->selectedAntenatalVisitType = 'unscheduled';
    }

    public function goToMaternalForm(): void
    {
        $patientId = $this->maternalPatientSelectionType === 'revisit'
            ? $this->revisitPatientId
            : $this->antenatalPatientId;

        if (! $patientId) {
            return;
        }

        $params = [
            'patient_id' => $patientId,
            'sub_option' => 'antenatal_care',
        ];

        if ($this->selectedAntenatalVisitType !== 'first_contact') {
            $params['start_step'] = 4;
        }

        if ($this->selectedAntenatalVisitType) {
            $params['visit_type'] = $this->selectedAntenatalVisitType;
        }

        $this->redirect(route('treatments.maternal.create', $params), navigate: true);
    }

    public function goBackToPatientSelection(): void
    {
        $this->patientId = null;
    }

    public function resetMaternalState(): void
    {
        $this->maternalFlow = '';
        $this->maternalSubCategory = '';
        $this->antenatalOption = '';
        $this->antenatalPatientId = null;
        $this->revisitPatientId = null;
        $this->maternalPatientSelectionType = '';
        $this->selectedAntenatalVisitType = '';
    }
}
