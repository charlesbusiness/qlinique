<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medication extends Model
{
    protected $fillable = [
        'treatment_chart_id',
        'drug_name',
        'quantity',
        'unit_cost',
        'total_cost',
        'dosage',
        'duration',
        'is_take_home',
    ];

    protected function casts(): array
    {
        return [
            'is_take_home' => 'boolean',
        ];
    }

    public function treatmentChart()
    {
        return $this->belongsTo(TreatmentChart::class);
    }
}
