<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PackageTimeConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packages_time_config', function (Blueprint $table) {
            $table->unsignedBigInteger('packages_id')->unsigned();
            $table->unsignedBigInteger('time_config_id')->unsigned();
        });

        // relations
        Schema::table('packages_time_config' , function(BluePrint $table){
           $table->foreign('packages_id')->references('id')->on('packages');
           $table->foreign('time_configs_id')->references('id')->on('time_configs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('package_timeConfig');
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
    }
}
