<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AntenatalVisit extends Model
{
    protected $fillable = [
        'maternal_health_record_id',
        'patient_id',
        'visit_number',
        'label',
        'scheduled_date',
        'status',
        'completed_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_date' => 'date',
            'completed_at' => 'datetime',
        ];
    }

    public function maternalHealthRecord()
    {
        return $this->belongsTo(MaternalHealthRecord::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
