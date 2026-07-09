<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lab_tests', function (Blueprint $table) {
            $table->string('sample_type')->nullable()->after('test_type');
            $table->decimal('amount', 10, 2)->default(0)->after('cost');
        });
    }

    public function down(): void
    {
        Schema::table('lab_tests', function (Blueprint $table) {
            $table->dropColumn(['sample_type', 'amount']);
        });
    }
};
