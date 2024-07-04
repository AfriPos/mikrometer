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
            $table->date('start_date');
            $table->date('end_date');
            $table->date('invoiced_till');
            $table->string('pppoe_login');
            $table->string('pppoe_password');
            $table->string('local_address');
            $table->string('remote_address');
            $table->string('service_price');
            $table->string('status');
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->unsignedBigInteger('pppoe_id')->nullable();
            $table->foreign('pppoe_id')->references('id')->on('pppoe_services');
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
