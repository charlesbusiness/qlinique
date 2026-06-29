<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('treatment_charts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();

            $table->string('category'); // checkup, treatment, emergency
            $table->date('visit_date');
            $table->text('presenting_complaint')->nullable();
            $table->text('symptoms')->nullable();
            $table->text('clinical_notes')->nullable();

            $table->text('previous_treatment_history')->nullable();

            $table->text('primary_diagnosis')->nullable();
            $table->text('secondary_diagnosis')->nullable();
            $table->text('diagnosis_notes')->nullable();

            $table->text('first_aid_intervention')->nullable();
            $table->datetime('first_aid_time')->nullable();
            $table->text('first_aid_outcome')->nullable();

            $table->text('treatment_plan')->nullable();
            $table->text('take_home_medication')->nullable();

            $table->string('treatment_schedule')->nullable(); // e.g., 3/7, 5/7
            $table->boolean('is_completed')->default(false);

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('treatment_charts');
    }
};
