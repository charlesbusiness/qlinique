<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->foreignId('patient_file_id')->nullable()->constrained('patient_files')->nullOnDelete()->after('patient_id');
        });

        DB::table('invoices')
            ->join('patients', 'invoices.patient_id', '=', 'patients.id')
            ->whereNull('invoices.patient_file_id')
            ->update(['invoices.patient_file_id' => DB::raw('patients.file_id')]);
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['patient_file_id']);
            $table->dropColumn('patient_file_id');
        });
    }
};
