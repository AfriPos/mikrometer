<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('status')->default('new');
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('portal_login')->nullable();
            $table->string('portal_password')->nullable();
            $table->string('service_type')->nullable();
            $table->string('category')->nullable();
            $table->string('billing_email')->nullable();
            $table->string('mpesa_phone')->nullable();
            $table->date('dob')->nullable();
            $table->string('id_number')->nullable();
            $table->string('street')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('city')->nullable();
            $table->string('geo_data')->nullable();
            $table->integer('account_balance')->default(0);
            $table->timestamps();
        });

        // Set the starting value for the auto-increment ID
        DB::statement("ALTER TABLE customers AUTO_INCREMENT = 1000;");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
