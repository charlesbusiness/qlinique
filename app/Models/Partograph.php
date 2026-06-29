<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partograph extends Model
{
    protected $fillable = [
        'antenatal_record_id',
        'cervical_dilation',
        'fetal_heart_rate',
        'maternal_pulse',
        'blood_pressure_systolic',
        'blood_pressure_diastolic',
        'temperature',
        'labour_progress',
        'recorded_at',
        'recorded_by',
    ];

    protected function casts(): array
    {
        return [
            'recorded_at' => 'datetime',
        ];
    }

    public function antenatalRecord()
    {
        return $this->belongsTo(AntenatalRecord::class);
    }
}
