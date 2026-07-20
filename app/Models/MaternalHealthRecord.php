<?php

namespace App\Models;

use App\Traits\HasAuditTrail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaternalHealthRecord extends Model
{
    use HasAuditTrail, HasFactory, SoftDeletes;

    protected $fillable = [
        'treatment_chart_id',
        'patient_id',
        'lmp',
        'cycle_regularity',
        'edd',
        'cga_weeks',
        'cga_days',
        'current_symptoms',
        'medications_exposures',
        'gravida',
        'term',
        'preterm',
        'abortion',
        'living',
        'prior_pregnancies',
        'prior_csection',
        'prior_csection_details',
        'chronic_conditions',
        'chronic_conditions_details',
        'infectious_disease_history',
        'prior_surgeries',
        'allergies',
        'current_medications',
        'family_genetic_history',
        'family_history_notes',
        'tobacco_vape',
        'tobacco_packs_per_day',
        'alcohol',
        'alcohol_drinks_per_week',
        'recreational_drugs',
        'recreational_drugs_details',
        'support_system',
        'safety_screening',
        'financial_stability',
        'intimate_partner_violence',
        'ipv_details',
        'occupation_hazard',
        'travel_history',
        'diet_intake',
        'physical_activities',
        'temperature',
        'temperature_unit',
        'pulse_bpm',
        'respiration_bpm',
        'bp_systolic',
        'bp_diastolic',
        'oxygen_saturation',
        'vitals_comment',
        'weight',
        'height',
        'bmi',
        'anthropometric_comment',
        'rme_fbs',
        'rme_rbs',
        'rme_pcv',
        'rme_rdta',
        'rme_glucose',
        'rme_protein',
        'rme_leukocytes',
        'rme_other_specify',
        'rme_other_result',
        'rme_comment',
        'cardio_resp',
        'cardio_resp_comment',
        'thyroid',
        'thyroid_comment',
        'breast',
        'breast_comment',
        'extremities',
        'extremities_comment',
        'fundal_height_cm',
        'fetal_lie',
        'fetal_presentation',
        'fetal_position',
        'fetal_engagement',
        'fetal_heart_rate_bpm',
        'pelvic_vaginal_findings',
        'lab_tests',
        'lab_investigation_comment',
        'clinical_judgement_diagnosis',
        'medications',
        'consent_enabled',
        'referral_letter',
        'next_visit_date',
        'schedule_type',
        'attending_physician_name',
        'attending_physician_signature',
        'attending_physician_signature_type',
        'attending_physician_date',
        'medical_bill',
        'bill_paid',
        'bill_outstanding',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'lmp' => 'date',
            'edd' => 'date',
            'next_visit_date' => 'date',
            'attending_physician_date' => 'date',
            'current_symptoms' => 'array',
            'prior_pregnancies' => 'array',
            'chronic_conditions' => 'array',
            'infectious_disease_history' => 'array',
            'family_genetic_history' => 'array',
            'diet_intake' => 'array',
            'physical_activities' => 'array',
            'cardio_resp' => 'array',
            'thyroid' => 'array',
            'breast' => 'array',
            'extremities' => 'array',
            'lab_tests' => 'array',
            'medications' => 'array',
            'medical_bill' => 'array',
            'consent_enabled' => 'boolean',
            'referral_letter' => 'boolean',
        ];
    }

    public function treatmentChart()
    {
        return $this->belongsTo(TreatmentChart::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function antenatalVisits()
    {
        return $this->hasMany(AntenatalVisit::class);
    }
}
