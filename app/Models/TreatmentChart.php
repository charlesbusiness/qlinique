<?php

namespace App\Models;

use App\Traits\HasAuditTrail;
use App\Traits\HasCompliance;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TreatmentChart extends Model
{
    use HasFactory, SoftDeletes, HasAuditTrail, HasCompliance;

    protected $fillable = [
        'patient_id',
        'category',
        'other_category',
        'visit_date',
        'presenting_complaint',
        'symptoms',
        'clinical_notes',
        'previous_treatment_history',
        'primary_diagnosis',
        'secondary_diagnosis',
        'diagnosis_notes',
        'first_aid_intervention',
        'first_aid_time',
        'first_aid_outcome',
        'treatment_plan',
        'take_home_medication',
        'treatment_schedule',
        'is_completed',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'visit_date' => 'date',
            'first_aid_time' => 'datetime',
            'is_completed' => 'boolean',
        ];
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function vitals()
    {
        return $this->hasMany(Vital::class);
    }

    public function medications()
    {
        return $this->hasMany(Medication::class);
    }

    public function labTests()
    {
        return $this->hasMany(LabTest::class);
    }

    public function complianceLogs()
    {
        return $this->hasMany(ComplianceLog::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
