<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhysicalExaminationRecord extends Model
{
    protected $fillable = [
        'treatment_chart_id',
        'section',
        'findings',
        'comment',
    ];

    protected function casts(): array
    {
        return [
            'findings' => 'array',
        ];
    }

    public function treatmentChart()
    {
        return $this->belongsTo(TreatmentChart::class);
    }
}
