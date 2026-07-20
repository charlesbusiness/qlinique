<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('maternal_health_records', function (Blueprint $table) {
            $table->string('attending_physician_signature_type')->default('typed')->after('attending_physician_signature');
        });
    }

    public function down(): void
    {
        Schema::table('maternal_health_records', function (Blueprint $table) {
            $table->dropColumn('attending_physician_signature_type');
        });
    }
};
