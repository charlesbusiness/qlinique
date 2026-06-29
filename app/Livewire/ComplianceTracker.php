<?php

namespace App\Livewire;

use App\Models\ComplianceLog;
use App\Models\TreatmentChart;
use Livewire\Component;

class ComplianceTracker extends Component
{
    public TreatmentChart $treatment;
    public array $logs = [];
    public bool $editing = false;

    public function mount(TreatmentChart $treatment): void
    {
        $this->treatment = $treatment;

        $this->logs = $treatment->complianceLogs()
            ->orderBy('date')
            ->get()
            ->toArray();
    }

    public function markAttended(int $day): void
    {
        $date = $this->treatment->visit_date->addDays($day);

        ComplianceLog::updateOrCreate(
            [
                'treatment_chart_id' => $this->treatment->id,
                'date' => $date,
            ],
            [
                'status' => 'attended',
                'marked_by' => auth()->id(),
            ]
        );

        $this->dispatch('$refresh');
    }

    public function markMissed(int $day): void
    {
        $date = $this->treatment->visit_date->addDays($day);

        ComplianceLog::updateOrCreate(
            [
                'treatment_chart_id' => $this->treatment->id,
                'date' => $date,
            ],
            [
                'status' => 'missed',
                'marked_by' => auth()->id(),
            ]
        );

        $this->dispatch('$refresh');
    }

    public function render()
    {
        $schedule = $this->treatment->treatment_schedule; // e.g., "3/7"
        $totalDays = 7;
        $expectedSessions = $schedule ? (int) explode('/', $schedule)[0] : 0;

        $attended = $this->treatment->complianceLogs()->where('status', 'attended')->count();
        $missed = $this->treatment->complianceLogs()->where('status', 'missed')->count();
        $percentage = $expectedSessions > 0 ? round(($attended / $expectedSessions) * 100, 1) : 0;

        $days = [];
        for ($i = 0; $i < $totalDays; $i++) {
            $date = $this->treatment->visit_date->addDays($i);
            $log = $this->treatment->complianceLogs()->whereDate('date', $date)->first();
            $days[] = [
                'day' => $i + 1,
                'date' => $date,
                'status' => $log?->status ?? 'pending',
            ];
        }

        return view('livewire.compliance-tracker', compact('days', 'attended', 'missed', 'percentage', 'expectedSessions'));
    }
}
