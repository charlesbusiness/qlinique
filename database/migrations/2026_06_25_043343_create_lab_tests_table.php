<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lab_tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('treatment_chart_id')->constrained()->cascadeOnDelete();

            $table->string('test_type');
            $table->text('findings')->nullable();
            $table->string('attachment_path')->nullable();
            $table->text('diagnosis_notes')->nullable();
            $table->text('case_history_notes')->nullable();
            $table->decimal('cost', 10, 2)->default(0);

            $table->boolean('is_completed')->default(false);
            $table->timestamp('completed_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lab_tests');
    }
};
