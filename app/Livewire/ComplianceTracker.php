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

    private function parseSchedule(): array
    {
        $schedule = $this->treatment->treatment_schedule;
        $parts = $schedule ? explode('/', $schedule) : [];

        $totalSlots = isset($parts[0]) ? (int) $parts[0] : 0;
        $rawType = $parts[1] ?? 'days';

        // Backward compat: old format stored numeric denominators (7/52/12)
        $unitType = match ($rawType) {
            '7', 'days' => 'days',
            '52', 'weeks' => 'weeks',
            '12', 'months' => 'months',
            default => 'days',
        };

        return [
            'totalSlots' => $totalSlots,
            'unitType' => $unitType,
        ];
    }

    public function mount(TreatmentChart $treatment): void
    {
        $this->treatment = $treatment;

        $this->logs = $treatment->complianceLogs()
            ->orderBy('date')
            ->get()
            ->toArray();
    }

    public function resolveSlotDate(int $slot): \Carbon\Carbon
    {
        $parsed = $this->parseSchedule();
        $unitType = $parsed['unitType'];

        return match ($unitType) {
            'weeks' => $this->treatment->visit_date->copy()->addWeeks($slot),
            'months' => $this->treatment->visit_date->copy()->addMonthsNoOverflow($slot),
            default => $this->treatment->visit_date->copy()->addDays($slot),
        };
    }

    public function markAttended(int $slot): void
    {
        $date = $this->resolveSlotDate($slot);

        if ($date->isFuture()) {
            return;
        }

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

    public function markMissed(int $slot): void
    {
        $date = $this->resolveSlotDate($slot);

        if ($date->isFuture()) {
            return;
        }

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
        $parsed = $this->parseSchedule();
        $totalSlots = $parsed['totalSlots'];
        $unitType = $parsed['unitType'];

        $unitLabels = [
            'days' => ['single' => 'day', 'plural' => 'days'],
            'weeks' => ['single' => 'week', 'plural' => 'weeks'],
            'months' => ['single' => 'month', 'plural' => 'months'],
        ];

        $label = $unitLabels[$unitType] ?? $unitLabels['days'];
        $expectedSessions = $totalSlots;

        $attended = $this->treatment->complianceLogs()->where('status', 'attended')->count();
        $missed = $this->treatment->complianceLogs()->where('status', 'missed')->count();
        $percentage = $expectedSessions > 0 ? round(($attended / $expectedSessions) * 100, 1) : 0;

        $entries = [];
        for ($i = 0; $i < $totalSlots; $i++) {
            if ($unitType === 'weeks') {
                $start = $this->treatment->visit_date->copy()->addWeeks($i);
                $end = $start->copy()->addDays(6);
                $date = $start;
                $log = $this->treatment->complianceLogs()
                    ->whereBetween('date', [$start, $end])
                    ->first();
                $slotLabel = 'Week ' . ($i + 1);
                $dateLabel = $start->format('d M') . ' - ' . $end->format('d M');
            } elseif ($unitType === 'months') {
                $start = $this->treatment->visit_date->copy()->addMonthsNoOverflow($i);
                $end = $start->copy()->addMonthNoOverflow()->subDay();
                $date = $start;
                $log = $this->treatment->complianceLogs()
                    ->whereBetween('date', [$start, $end])
                    ->first();
                $slotLabel = $start->format('M Y');
                $dateLabel = $start->format('d M') . ' - ' . $end->format('d M');
            } else {
                $date = $this->treatment->visit_date->copy()->addDays($i);
                $log = $this->treatment->complianceLogs()->whereDate('date', $date)->first();
                $slotLabel = 'Day ' . ($i + 1);
                $dateLabel = $date->format('d M');
            }

            $entries[] = [
                'index' => $i,
                'label' => $slotLabel,
                'date_label' => $dateLabel,
                'date' => $date,
                'status' => $log?->status ?? 'pending',
            ];
        }

        return view('livewire.compliance-tracker', compact(
            'entries', 'attended', 'missed', 'percentage',
            'expectedSessions', 'unitType', 'label', 'totalSlots'
        ));
    }
}
