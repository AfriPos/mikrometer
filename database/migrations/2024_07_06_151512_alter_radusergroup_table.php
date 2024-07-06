<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('radusergroup', function (Blueprint $table) {
            $table->string('service_price')->nullable()->after('priority');
            $table->integer('service_duration')->nullable()->after('service_price');
            $table->string('duration_unit')->nullable()->after('service_duration');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('radusergroup', function (Blueprint $table) {
            $table->dropColumn('service_price');
            $table->dropColumn('service_duration');
            $table->dropColumn('duration_unit');
        });
    }
};
