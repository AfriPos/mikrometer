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
        Schema::create('customer_subscription', function (Blueprint $table) {
            $table->id();
            $table->dateTime('start_date');
            $table->dateTime('end_date')->nullable();
            $table->dateTime('invoiced_till')->nullable();
            $table->string('pppoe_login');
            $table->string('pppoe_password');
            $table->string('status');
            $table->string('ipaddress');
            $table->string('service');
            $table->unsignedBigInteger('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->integer('service_price');
            $table->foreign('service')->references('service_name')->on('pppoe_services');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_subscription');
    }
};
