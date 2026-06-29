<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('treatment_charts', function (Blueprint $table) {
            $table->text('recommendations')->nullable()->after('diagnosis_notes');
        });
    }

    public function down(): void
    {
        Schema::table('treatment_charts', function (Blueprint $table) {
            $table->dropColumn('recommendations');
        });
    }
};
