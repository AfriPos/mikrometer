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
        Schema::create('ip_pools', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->string('network');
            $table->timestamps();
        });
    }
    public $incrementing = false; // This ensures that the ID is not auto-incrementing
    protected $keyType = 'string'; // This sets the primary key type to string
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ip_pools');
    }
};
