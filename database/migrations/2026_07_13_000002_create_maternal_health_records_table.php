<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maternal_health_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('treatment_chart_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();

            // Pregnancy dating
            $table->date('lmp')->nullable();
            $table->string('cycle_regularity')->nullable();
            $table->date('edd')->nullable();
            $table->smallInteger('cga_weeks')->nullable();
            $table->smallInteger('cga_days')->nullable();
            $table->json('current_symptoms')->nullable();
            $table->text('medications_exposures')->nullable();

            // Obstetric history (GTPAL)
            $table->string('gravida')->nullable();
            $table->string('term')->nullable();
            $table->string('preterm')->nullable();
            $table->string('abortion')->nullable();
            $table->string('living')->nullable();
            $table->json('prior_pregnancies')->nullable();
            $table->string('prior_csection')->nullable();
            $table->text('prior_csection_details')->nullable();

            // Medical history
            $table->json('chronic_conditions')->nullable();
            $table->text('chronic_conditions_details')->nullable();
            $table->json('infectious_disease_history')->nullable();
            $table->text('prior_surgeries')->nullable();
            $table->text('allergies')->nullable();
            $table->text('current_medications')->nullable();

            // Family/genetic history
            $table->json('family_genetic_history')->nullable();
            $table->text('family_history_notes')->nullable();

            // Social/environmental history
            $table->string('tobacco_vape')->nullable();
            $table->smallInteger('tobacco_packs_per_day')->nullable();
            $table->string('alcohol')->nullable();
            $table->smallInteger('alcohol_drinks_per_week')->nullable();
            $table->string('recreational_drugs')->nullable();
            $table->text('recreational_drugs_details')->nullable();
            $table->string('support_system')->nullable();
            $table->string('safety_screening')->nullable();
            $table->string('financial_stability')->nullable();
            $table->string('intimate_partner_violence')->nullable();
            $table->text('ipv_details')->nullable();
            $table->string('occupation_hazard')->nullable();
            $table->text('travel_history')->nullable();
            $table->json('diet_intake')->nullable();
            $table->json('physical_activities')->nullable();

            // Vitals
            $table->decimal('temperature', 5, 1)->nullable();
            $table->string('temperature_unit', 10)->default('celsius');
            $table->smallInteger('pulse_bpm')->nullable();
            $table->smallInteger('respiration_bpm')->nullable();
            $table->smallInteger('bp_systolic')->nullable();
            $table->smallInteger('bp_diastolic')->nullable();
            $table->text('vitals_comment')->nullable();

            // Anthropometry
            $table->decimal('weight', 6, 2)->nullable();
            $table->decimal('height', 6, 2)->nullable();
            $table->decimal('bmi', 5, 1)->nullable();
            $table->text('anthropometric_comment')->nullable();

            // RME
            $table->decimal('rme_fbs', 8, 2)->nullable();
            $table->decimal('rme_rbs', 8, 2)->nullable();
            $table->decimal('rme_pcv', 5, 1)->nullable();
            $table->string('rme_rdta', 5)->nullable();
            $table->string('rme_glucose', 5)->nullable();
            $table->string('rme_protein', 5)->nullable();
            $table->text('rme_leukocytes')->nullable();
            $table->text('rme_other_specify')->nullable();
            $table->string('rme_other_result', 5)->nullable();
            $table->text('rme_comment')->nullable();

            // Physical exam
            $table->json('cardio_resp')->nullable();
            $table->text('cardio_resp_comment')->nullable();
            $table->json('thyroid')->nullable();
            $table->text('thyroid_comment')->nullable();
            $table->json('breast')->nullable();
            $table->text('breast_comment')->nullable();
            $table->json('extremities')->nullable();
            $table->text('extremities_comment')->nullable();

            // Obstetric exam
            $table->decimal('fundal_height_cm', 5, 1)->nullable();
            $table->string('fetal_lie')->nullable();
            $table->string('fetal_presentation')->nullable();
            $table->text('fetal_position')->nullable();
            $table->text('fetal_engagement')->nullable();
            $table->smallInteger('fetal_heart_rate_bpm')->nullable();
            $table->text('pelvic_vaginal_findings')->nullable();

            // Investigation + Diagnosis
            $table->json('lab_tests')->nullable();
            $table->text('lab_investigation_comment')->nullable();
            $table->text('clinical_judgement_diagnosis')->nullable();

            // Treatment plan
            $table->json('medications')->nullable();

            // Consent/Billing/Meta
            $table->boolean('consent_enabled')->default(false);
            $table->boolean('referral_letter')->default(false);
            $table->date('next_visit_date')->nullable();
            $table->string('attending_physician_name')->nullable();
            $table->string('attending_physician_signature')->nullable();
            $table->date('attending_physician_date')->nullable();
            $table->json('medical_bill')->nullable();
            $table->decimal('bill_paid', 12, 2)->default(0);
            $table->decimal('bill_outstanding', 12, 2)->default(0);

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maternal_health_records');
    }
};
