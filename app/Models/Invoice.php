<?php

namespace App\Models;

use App\Traits\HasAuditTrail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasAuditTrail, HasFactory, SoftDeletes;

    protected $fillable = [
        'invoice_number',
        'patient_id',
        'patient_file_id',
        'treatment_chart_id',
        'status',
        'amount_due',
        'amount_paid',
        'balance',
        'notes',
        'created_by',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function patientFile()
    {
        return $this->belongsTo(PatientFile::class);
    }

    public function treatmentChart()
    {
        return $this->belongsTo(TreatmentChart::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function isFullyPaid(): bool
    {
        return $this->balance <= 0;
    }

    public function getAccountTypeAttribute(): ?string
    {
        return $this->patientFile?->type;
    }
}
