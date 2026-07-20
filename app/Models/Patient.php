<?php

namespace App\Models;

use App\Traits\HasAuditTrail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use HasAuditTrail, HasFactory, SoftDeletes;

    protected $fillable = [
        'file_id',
        'name',
        'gender',
        'date_of_birth',
        'phone',
        'email',
        'address',
        'occupation',
        'marital_status',
        'blood_group',
        'genotype',
        'photo_path',
        'patient_type',
        'next_of_kin',
        'consent',
        'religion',
        'denomination',
        'signature_type',
        'signature',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'next_of_kin' => 'array',
            'consent' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public static function patientTypeOptions(): array
    {
        return [
            'admission' => 'Full Admission',
            'outpatient' => 'Out-patient',
            'outreach' => 'Outreach (Home Service)',
            'antenatal' => 'Antenatal',
        ];
    }

    public function getPatientTypeLabelAttribute(): ?string
    {
        return static::patientTypeOptions()[$this->patient_type] ?? null;
    }

    public function file()
    {
        return $this->belongsTo(PatientFile::class, 'file_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function treatmentCharts()
    {
        return $this->hasMany(TreatmentChart::class);
    }

    public function antenatalRecords()
    {
        return $this->hasMany(AntenatalRecord::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }
}
