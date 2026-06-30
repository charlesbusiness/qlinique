<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::rename('family_files', 'patient_files');

        Schema::table('patient_files', function (Blueprint $table) {
            $table->string('email')->nullable()->change();
            $table->string('phone')->nullable()->change();
        });

        Schema::table('patients', function (Blueprint $table) {
            $table->foreignId('file_id')->nullable()->after('id');
        });

        DB::transaction(function () {
            $individualPatients = DB::table('patients')
                ->whereNull('family_file_id')
                ->orderBy('id')
                ->get();

            foreach ($individualPatients as $patient) {
                $fileId = DB::table('patient_files')->insertGetId([
                    'file_number' => $patient->file_number,
                    'name' => $patient->name,
                    'email' => $patient->email ?? '',
                    'phone' => $patient->phone ?? '',
                    'address' => $patient->address,
                    'type' => 'individual',
                    'created_at' => $patient->created_at,
                    'updated_at' => $patient->updated_at,
                ]);

                DB::table('patients')
                    ->where('id', $patient->id)
                    ->update(['file_id' => $fileId]);
            }

            DB::table('patients')
                ->whereNotNull('family_file_id')
                ->update(['file_id' => DB::raw('family_file_id')]);
        });

        Schema::table('patients', function (Blueprint $table) {
            $table->dropForeign(['family_file_id']);
            $table->dropColumn(['family_file_id', 'file_number', 'account_type']);
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('account_type');
        });

        Schema::table('patients', function (Blueprint $table) {
            DB::statement('ALTER TABLE patients MODIFY file_id BIGINT UNSIGNED NOT NULL');
            $table->foreign('file_id')->references('id')->on('patient_files')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('account_type')->nullable()->after('treatment_chart_id');
        });

        Schema::table('patients', function (Blueprint $table) {
            $table->dropForeign(['file_id']);
            $table->string('file_number')->nullable()->unique();
            $table->string('account_type')->nullable();
            $table->foreignId('family_file_id')->nullable()->constrained('patient_files')->nullOnDelete();
        });

        DB::statement("
            UPDATE patients p
            INNER JOIN patient_files pf ON p.file_id = pf.id
            SET p.file_number = pf.file_number,
                p.account_type = pf.type,
                p.family_file_id = CASE WHEN pf.type = 'individual' THEN NULL ELSE pf.id END
        ");

        DB::table('patient_files')->where('type', 'individual')->delete();

        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn('file_id');
            $table->string('file_number')->nullable(false)->change();
            $table->string('account_type')->nullable(false)->change();
        });

        Schema::table('patient_files', function (Blueprint $table) {
            $table->string('email')->nullable(false)->change();
            $table->string('phone')->nullable(false)->change();
        });

        Schema::rename('patient_files', 'family_files');
    }
};
