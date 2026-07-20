<?php

namespace App\Models;

use App\Traits\HasAuditTrail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AntenatalRecord extends Model
{
    use HasAuditTrail, HasFactory, SoftDeletes;

    protected $fillable = [
        'patient_id',
        'edd',
        'gestation_weeks',
        'obstetric_history',
        'risk_level',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'edd' => 'date',
        ];
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function partographs()
    {
        return $this->hasMany(Partograph::class);
    }
}
