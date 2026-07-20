<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('antenatal_visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('maternal_health_record_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('visit_number');
            $table->string('label')->nullable();
            $table->date('scheduled_date');
            $table->string('status')->default('scheduled'); // scheduled, completed, missed, skipped
            $table->timestamp('completed_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['patient_id', 'status']);
            $table->index(['maternal_health_record_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('antenatal_visits');
    }
};
