<?php

namespace App\Traits;

trait HasCompliance
{
    public function compliancePercentage(): float
    {
        $total = $this->complianceLogs()->count();
        if ($total === 0) {
            return 0.0;
        }

        $attended = $this->complianceLogs()->where('status', 'attended')->count();
        return round(($attended / $total) * 100, 2);
    }

    public function missedSessions()
    {
        return $this->complianceLogs()->where('status', 'missed')->get();
    }
}
