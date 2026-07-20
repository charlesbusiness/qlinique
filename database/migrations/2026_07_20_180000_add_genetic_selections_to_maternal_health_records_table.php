<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('maternal_health_records', function (Blueprint $table) {
            $table->string('genetic_errors_selection')->nullable()->after('family_genetic_history');
            $table->string('heart_defects_selection')->nullable()->after('genetic_errors_selection');
            $table->string('family_history_selection')->nullable()->after('heart_defects_selection');
        });
    }

    public function down(): void
    {
        Schema::table('maternal_health_records', function (Blueprint $table) {
            $table->dropColumn([
                'genetic_errors_selection',
                'heart_defects_selection',
                'family_history_selection',
            ]);
        });
    }
};
