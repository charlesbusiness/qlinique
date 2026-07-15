<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('treatment_charts')
            ->where('category', 'antenatal')
            ->update(['category' => 'maternal_health']);

        DB::table('treatment_charts')
            ->where('category', 'enrollment_treatment_management')
            ->update(['category' => 'enrollment_palliative']);

        DB::table('treatment_charts')
            ->where('category', 'emergency')
            ->update(['category' => 'emergency_accident']);
    }

    public function down(): void
    {
        DB::table('treatment_charts')
            ->where('category', 'maternal_health')
            ->update(['category' => 'antenatal']);

        DB::table('treatment_charts')
            ->where('category', 'enrollment_palliative')
            ->update(['category' => 'enrollment_treatment_management']);

        DB::table('treatment_charts')
            ->where('category', 'emergency_accident')
            ->update(['category' => 'emergency']);
    }
};
