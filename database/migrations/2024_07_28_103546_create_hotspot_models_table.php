<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('hotspot_plans', function (Blueprint $table) {
            $table->id();
            $table->string('plan_name');
            $table->decimal('plan_price', 10, 2);
            $table->integer('data_limit');
            $table->string('data_limit_unit');
            $table->integer('validity');
            $table->string('validity_unit');
            $table->integer('speed_limit');
            $table->string('speed_limit_unit');
            $table->enum('simultaneous_use', ['yes', 'no'])->default('no');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotspot_plans');
    }
};
