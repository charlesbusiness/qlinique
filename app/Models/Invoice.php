<?php

namespace App\Models;

use App\Traits\HasAuditTrail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Model
{
    use HasFactory, SoftDeletes, HasAuditTrail;

    protected $fillable = [
        'invoice_number',
        'patient_id',
        'treatment_chart_id',
        'account_type',
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

    public function treatmentChart()
    {
        return $this->belongsTo(TreatmentChart::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function isFullyPaid(): bool
    {
        return $this->balance <= 0;
    }
}
