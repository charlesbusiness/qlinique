<?php

namespace App\Models;

use App\Traits\HasAuditTrail;
use App\Traits\HasFileNumber;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Patient extends Model
{
    use HasFactory, SoftDeletes, HasFileNumber, HasAuditTrail;

    protected $fillable = [
        'file_number',
        'name',
        'gender',
        'date_of_birth',
        'phone',
        'email',
        'address',
        'occupation',
        'marital_status',
        'photo_path',
        'account_type',
        'patient_type',
        'family_file_id',
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
        ];
    }

    public function getPatientTypeLabelAttribute(): ?string
    {
        return static::patientTypeOptions()[$this->patient_type] ?? null;
    }

    public function familyFile()
    {
        return $this->belongsTo(FamilyFile::class, 'family_file_id');
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
}
