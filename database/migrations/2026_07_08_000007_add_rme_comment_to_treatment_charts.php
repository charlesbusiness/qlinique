<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('treatment_charts', function (Blueprint $table) {
            $table->text('rme_comment')->nullable()->after('medical_bill');
        });
    }

    public function down(): void
    {
        Schema::table('treatment_charts', function (Blueprint $table) {
            $table->dropColumn('rme_comment');
        });
    }
};
