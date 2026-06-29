<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vital extends Model
{
    protected $fillable = [
        'treatment_chart_id',
        'temperature',
        'blood_pressure_systolic',
        'blood_pressure_diastolic',
        'pulse_rate',
        'respiratory_rate',
        'weight',
        'height',
        'bmi',
        'oxygen_saturation',
        'temperature_unit',
        'recorded_by',
    ];

    public function treatmentChart()
    {
        return $this->belongsTo(TreatmentChart::class);
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
