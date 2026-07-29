<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vitals', function (Blueprint $table) {
            $table->string('bmi_range')->nullable()->after('bmi');
        });

        Schema::table('maternal_health_records', function (Blueprint $table) {
            $table->string('bmi_range')->nullable()->after('bmi');
        });
    }

    public function down(): void
    {
        Schema::table('vitals', function (Blueprint $table) {
            $table->dropColumn('bmi_range');
        });

        Schema::table('maternal_health_records', function (Blueprint $table) {
            $table->dropColumn('bmi_range');
        });
    }
};
