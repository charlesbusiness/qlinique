<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rme_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('treatment_chart_id')->constrained()->cascadeOnDelete();
            $table->string('test_name'); // FBS, RBS, SPO2, PT, Cholesterol, PCV, RDTA, XYZ, Other
            $table->string('result')->nullable();
            $table->decimal('amount', 10, 2)->default(0);
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rme_results');
    }
};
