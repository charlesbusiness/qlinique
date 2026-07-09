<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('treatment_charts', function (Blueprint $table) {
            $table->text('finding_on_history')->nullable()->after('visit_date');
            $table->text('recommended_drugs')->nullable()->after('previous_treatment_history');
            $table->text('allergies')->nullable()->after('recommended_drugs');
            $table->boolean('is_draft')->default(false)->after('is_completed');
            $table->unsignedTinyInteger('current_step')->nullable()->after('is_draft');
            $table->boolean('consent_enabled')->default(false)->after('consent');
            $table->json('medical_bill')->nullable()->after('consent_enabled');
        });
    }

    public function down(): void
    {
        Schema::table('treatment_charts', function (Blueprint $table) {
            $table->dropColumn(['finding_on_history', 'recommended_drugs', 'allergies', 'is_draft', 'current_step', 'consent_enabled', 'medical_bill']);
        });
    }
};
