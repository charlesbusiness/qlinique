<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('maternal_health_records', function (Blueprint $table) {
            $table->decimal('rme_fbs_amount', 10, 2)->nullable()->after('rme_fbs');
            $table->decimal('rme_rbs_amount', 10, 2)->nullable()->after('rme_rbs');
            $table->decimal('rme_pcv_amount', 10, 2)->nullable()->after('rme_pcv');
            $table->decimal('rme_rdta_amount', 10, 2)->nullable()->after('rme_rdta');
            $table->decimal('rme_glucose_amount', 10, 2)->nullable()->after('rme_glucose');
            $table->decimal('rme_protein_amount', 10, 2)->nullable()->after('rme_protein');
            $table->decimal('rme_leukocytes_amount', 10, 2)->nullable()->after('rme_leukocytes');
            $table->decimal('rme_other_amount', 10, 2)->nullable()->after('rme_other_result');
        });
    }

    public function down(): void
    {
        Schema::table('maternal_health_records', function (Blueprint $table) {
            $table->dropColumn([
                'rme_fbs_amount',
                'rme_rbs_amount',
                'rme_pcv_amount',
                'rme_rdta_amount',
                'rme_glucose_amount',
                'rme_protein_amount',
                'rme_leukocytes_amount',
                'rme_other_amount',
            ]);
        });
    }
};
