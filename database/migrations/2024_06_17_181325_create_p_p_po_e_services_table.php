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
        Schema::create('pppoe_services', function (Blueprint $table) {
            $table->id();
            $table->string('interface');
            $table->string('service_name');
            $table->integer('max_mtu');
            $table->integer('max_mru');
            $table->string('service_price');
            $table->unsignedBigInteger('profile_id');
            $table->string('disabled');
            $table->foreign('profile_id')->references('id')->on('pppoe_profiles');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pppoe_services');
    }
};
