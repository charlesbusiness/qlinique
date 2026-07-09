<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('physical_examination_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('treatment_chart_id')->constrained()->cascadeOnDelete();
            $table->string('section'); // heart_lungs, eyes_ears_nose_throat, abdominal, reflex, hair, skin
            $table->json('findings')->nullable(); // checked items
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('physical_examination_records');
    }
};
