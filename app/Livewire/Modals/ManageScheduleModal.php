<?php

namespace App\Livewire\Modals;

use App\Livewire\TreatmentComponents\MaternalHealth\WithMaternalFormOptions;
use App\Models\AntenatalVisit;
use App\Models\MaternalHealthRecord;
use Livewire\Attributes\On;
use Livewire\Component;

class ManageScheduleModal extends Component
{
    use WithMaternalFormOptions;

    public bool $showModal = false;

    public bool $isInline = false;

    public ?int $recordId = null;

    public ?int $patientId = null;

    public string $visitType = '';

    public array $schedule_visits = [];

    public int $currentDoseNumber = 1;

    public function mount(
        ?int $recordId = null,
        ?int $patientId = null,
        string $visitType = '',
        bool $isInline = false,
    ): void {
        $this->recordId = $recordId;
        $this->patientId = $patientId;
        $this->visitType = $visitType;
        $this->isInline = $isInline;

        if ($isInline && $recordId && $patientId) {
            $this->showModal = true;
            $this->loadSchedule();
            $this->loadCurrentDoseNumber();
        }
    }

    #[On('open-schedule-modal')]
    public function openModal(int $recordId, int $patientId, string $visitType): void
    {
        $this->recordId = $recordId;
        $this->patientId = $patientId;
        $this->visitType = $visitType;
        $this->loadSchedule();
        $this->loadCurrentDoseNumber();
        $this->showModal = true;
    }

    private function loadCurrentDoseNumber(): void
    {
        if ($this->visitType === 'first_contact') {
            $this->currentDoseNumber = 1;

            return;
        }

        $nextVisit = AntenatalVisit::where('patient_id', $this->patientId)
            ->where('status', 'scheduled')
            ->orderBy('visit_number')
            ->first();

        if ($nextVisit) {
            $this->currentDoseNumber = $nextVisit->visit_number;

            return;
        }

        $this->currentDoseNumber = AntenatalVisit::where('patient_id', $this->patientId)
            ->where('status', 'completed')
            ->count() + 1;
    }

    private function loadSchedule(): void
    {
        if (! $this->recordId || ! $this->patientId) {
            return;
        }

        $record = MaternalHealthRecord::with(['antenatalVisits', 'treatmentChart'])->find($this->recordId);
        if (! $record) {
            return;
        }

        $visits = [];

        if ($record->antenatalVisits->isNotEmpty()) {
            $visits = $record->antenatalVisits
                ->sortBy('visit_number')
                ->map(fn ($v) => [
                    'id' => $v->id,
                    'label' => $v->label,
                    'duration' => '',
                    'date' => $v->scheduled_date->format('Y-m-d'),
                    'status' => $v->status,
                ])
                ->values()
                ->toArray();
        }

        $firstContactDate = $record->treatmentChart?->visit_date?->format('Y-m-d') ?? '';

        $this->schedule_visits = array_merge([
            [
                'id' => null,
                'label' => '1st Antenatal Visit (First Contact)',
                'duration' => '',
                'date' => $firstContactDate,
                'status' => 'completed',
            ],
        ], $visits);
    }

    public function addVisit(): void
    {
        $nextNum = count($this->schedule_visits) + 1;
        $this->schedule_visits[] = [
            'id' => null,
            'label' => $this->ordinal($nextNum) . ' Antenatal Visit',
            'duration' => '',
            'date' => '',
            'status' => 'scheduled',
        ];
    }

    public function removeVisit(int $index): void
    {
        if ($index === 0) {
            return;
        }

        unset($this->schedule_visits[$index]);
        $this->schedule_visits = array_values($this->schedule_visits);
    }

    public function calculateDate(int $index): void
    {
        if ($index === 0) {
            return;
        }

        $duration = $this->schedule_visits[$index]['duration'] ?? '';

        if (empty($duration)) {
            return;
        }

        $this->schedule_visits[$index]['date'] = match ($duration) {
            '2_weeks' => now()->addWeeks(2)->format('Y-m-d'),
            '4_weeks' => now()->addWeeks(4)->format('Y-m-d'),
            '1_month' => now()->addMonth()->format('Y-m-d'),
            '2_months' => now()->addMonths(2)->format('Y-m-d'),
            '3_months' => now()->addMonths(3)->format('Y-m-d'),
            '6_months' => now()->addMonths(6)->format('Y-m-d'),
            default => $this->schedule_visits[$index]['date'] ?? '',
        };
    }

    public function saveSchedule(): void
    {
        if (! $this->recordId || ! $this->patientId) {
            return;
        }

        $record = MaternalHealthRecord::find($this->recordId);
        if (! $record) {
            return;
        }

        // Skip index 0 (first contact — read-only, already completed)
        $editable = array_slice($this->schedule_visits, 1);
        $visits = array_values(array_filter($editable, fn ($v) => ! empty($v['date'])));

        $record->antenatalVisits()->delete();

        foreach ($visits as $i => $visit) {
            AntenatalVisit::create([
                'maternal_health_record_id' => $record->id,
                'patient_id' => $this->patientId,
                'visit_number' => $i + 2,
                'label' => $visit['label'] ?? ($this->ordinal($i + 2) . ' Antenatal Visit'),
                'scheduled_date' => $visit['date'],
                'status' => $visit['status'] ?? 'scheduled',
            ]);
        }

        if ($this->isInline) {
            $this->loadSchedule();
            $this->loadCurrentDoseNumber();
        } else {
            $this->showModal = false;
            $this->dispatch('schedule-saved');
        }
    }

    public function closeModal(): void
    {
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.modals.manage-schedule-modal');
    }
}
