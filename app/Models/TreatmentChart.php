<?php

namespace App\Models;

use App\Traits\HasAuditTrail;
use App\Traits\HasCompliance;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TreatmentChart extends Model
{
    use HasAuditTrail, HasCompliance, HasFactory, SoftDeletes;

    protected $fillable = [
        'patient_id',
        'category',
        'other_category',
        'sub_category',
        'visit_date',
        'presenting_complaint',
        'symptoms',
        'clinical_notes',
        'previous_treatment_history',
        'recommended_drugs',
        'allergies',
        'finding_on_history',
        'primary_diagnosis',
        'secondary_diagnosis',
        'diagnosis_notes',
        'recommendations',
        'first_aid_intervention',
        'first_aid_time',
        'first_aid_outcome',
        'treatment_plan',
        'take_home_medication',
        'treatment_schedule',
        'consent',
        'consent_enabled',
        'medical_bill',
        'rme_comment',
        'is_completed',
        'is_draft',
        'current_step',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'visit_date' => 'date',
            'first_aid_time' => 'datetime',
            'is_completed' => 'boolean',
            'is_draft' => 'boolean',
            'consent_enabled' => 'boolean',
            'consent' => 'array',
            'medical_bill' => 'array',
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

    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    public function physicalExaminations()
    {
        return $this->hasMany(PhysicalExaminationRecord::class);
    }

    public function rmeResults()
    {
        return $this->hasMany(RmeResult::class);
    }

    public function treatmentPlanItems()
    {
        return $this->hasMany(TreatmentPlanItem::class);
    }

    public function maternalHealthRecord()
    {
        return $this->hasOne(MaternalHealthRecord::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
