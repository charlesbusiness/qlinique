<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('family_files', function (Blueprint $table) {
            $table->id();
            $table->string('file_number')->unique();
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->text('address')->nullable();
            $table->string('type'); // family, corporate
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('family_files');
    }
};
