<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('treatment_plan_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('treatment_chart_id')->constrained()->cascadeOnDelete();
            $table->string('route_category'); // oral, parenteral, buccal, sublingual, inhalation, topical, rectal, vaginal, drop
            $table->string('route_form'); // tablet, capsule, syrup, IV, IM, etc.
            $table->string('drug_name');
            $table->string('strength')->nullable();
            $table->string('dosage')->nullable();
            $table->string('regime'); // dly, bd, tds, qds, nocte, stat, PRN
            $table->unsignedSmallInteger('length_value');
            $table->string('length_unit'); // days, weeks, months
            $table->string('length_display'); // x/7, x/52, x/12
            $table->decimal('amount', 10, 2)->default(0);
            $table->boolean('is_take_home')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('treatment_plan_items');
    }
};
