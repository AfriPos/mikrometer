<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRadiusTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create radcheck table
        Schema::create('radcheck', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username', 64)->index();
            $table->string('attribute', 64);
            $table->char('op', 2)->default('==');
            $table->string('value', 253);
            $table->unsignedBigInteger('customer_subscription_id');
            $table->foreign('customer_subscription_id')->references('id')->on('customer_subscription');
        });

        // Create radreply table
        Schema::create('radreply', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username', 64)->index();
            $table->string('attribute', 64);
            $table->char('op', 2)->default('=');
            $table->string('value', 253);
            $table->unsignedBigInteger('customer_subscription_id')->nullable();
            $table->foreign('customer_subscription_id')->references('id')->on('customer_subscription');
        });

        // Create radgroupcheck table
        Schema::create('radgroupcheck', function (Blueprint $table) {
            $table->increments('id');
            $table->string('groupname', 64)->index();
            $table->string('attribute', 64);
            $table->char('op', 2)->default('==');
            $table->string('value');
        });

        // Create radgroupreply table
        Schema::create('radgroupreply', function (Blueprint $table) {
            $table->increments('id');
            $table->string('groupname', 64)->index();
            $table->string('attribute', 64);
            $table->char('op', 2)->default('=');
            $table->string('value');
        });

        // Create radusergroup table
        Schema::create('radusergroup', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username', 64)->index();
            $table->string('groupname', 64);
            $table->integer('priority')->default(1);
        });

        // Create radacct table
        Schema::create('radacct', function (Blueprint $table) {
            $table->bigIncrements('radacctid');
            $table->string('acctsessionid', 64)->default('')->index();
            $table->string('acctuniqueid', 32)->default('')->unique();
            $table->string('username', 64)->default('')->index();
            $table->string('realm', 64)->nullable();
            $table->string('nasipaddress', 15)->default('')->index();
            $table->string('nasportid', 32)->nullable();
            $table->string('nasporttype', 32)->nullable();
            $table->datetime('acctstarttime')->nullable()->index();
            $table->datetime('acctupdatetime')->nullable();
            $table->datetime('acctstoptime')->nullable()->index();
            $table->integer('acctinterval')->nullable()->index();
            $table->unsignedInteger('acctsessiontime')->nullable()->index();
            $table->string('acctauthentic', 32)->nullable();
            $table->string('connectinfo_start', 128)->nullable();
            $table->string('connectinfo_stop', 128)->nullable();
            $table->bigInteger('acctinputoctets')->nullable();
            $table->bigInteger('acctoutputoctets')->nullable();
            $table->string('calledstationid', 50)->default('');
            $table->string('callingstationid', 50)->default('');
            $table->string('acctterminatecause', 32)->default('');
            $table->string('servicetype', 32)->nullable();
            $table->string('framedprotocol', 32)->nullable();
            $table->string('framedipaddress', 15)->default('')->index();
            $table->string('framedipv6address', 45)->default('')->index();
            $table->string('framedipv6prefix', 45)->default('')->index();
            $table->string('framedinterfaceid', 44)->default('')->index();
            $table->string('delegatedipv6prefix', 45)->default('')->index();
            $table->string('class', 64)->nullable()->index();
        });

        Schema::create('nas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nasname', 128)->index();
            $table->string('shortname', 32)->nullable();
            $table->string('type', 30)->nullable();
            $table->integer('ports')->nullable();
            $table->string('radius_server_ip', 191);
            $table->string('secret', 60);
            $table->string('server', 64)->nullable();
            $table->string('username', 64)->nullable();
            $table->string('password', 191)->nullable();
            $table->string('community', 50)->nullable();
            $table->string('description', 200)->nullable()->default('RADIUS CLIENT');
            $table->string('ip_pool')->nullable();
            $table->boolean('configured')->nullable()->default(false);
        });

        Schema::create('nasreload', function (Blueprint $table) {
            $table->string('nasipaddress', 15)->primary();
            $table->dateTime('reloadtime');
        });

        Schema::create('radpostauth', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username', 64)->default('');
            $table->string('pass', 64)->default('');
            $table->string('reply', 32)->default('');
            $table->timestamp('authdate', 6)->useCurrent()->useCurrentOnUpdate();
            $table->string('class', 64)->nullable();
        });

        // Create services table
        // Schema::create('services', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('name');
        //     $table->text('description')->nullable();
        //     $table->integer('duration');
        //     $table->string('duration_unit');
        //     $table->decimal('price', 10, 2);
        // });

        // Create user_services table
        // Schema::create('user_services', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('username');
        //     $table->foreign('username')->references('username')->on('radcheck')->onDelete('cascade');
        //     $table->unsignedBigInteger('service_id');
        //     $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
        //     $table->date('start_date');
        //     $table->date('end_date');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('user_services');
        // Schema::dropIfExists('services');
        Schema::dropIfExists('radacct');
        Schema::dropIfExists('radusergroup');
        Schema::dropIfExists('radgroupreply');
        Schema::dropIfExists('radgroupcheck');
        Schema::dropIfExists('radreply');
        Schema::dropIfExists('radcheck');
        Schema::dropIfExists('nas');
        Schema::dropIfExists('nasreload');
    }
}
