<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('partographs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('antenatal_record_id')->constrained()->cascadeOnDelete();

            $table->integer('cervical_dilation')->nullable();
            $table->integer('fetal_heart_rate')->nullable();
            $table->integer('maternal_pulse')->nullable();
            $table->string('blood_pressure_systolic')->nullable();
            $table->string('blood_pressure_diastolic')->nullable();
            $table->decimal('temperature', 5, 2)->nullable();
            $table->text('labour_progress')->nullable();

            $table->timestamp('recorded_at');
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partographs');
    }
};
