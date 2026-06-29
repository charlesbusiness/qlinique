<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComplianceLog extends Model
{
    protected $fillable = [
        'treatment_chart_id',
        'date',
        'status',
        'notes',
        'marked_by',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }

    public function treatmentChart()
    {
        return $this->belongsTo(TreatmentChart::class);
    }

    public function markedBy()
    {
        return $this->belongsTo(User::class, 'marked_by');
    }
}
