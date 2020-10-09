<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->string('image')->default('unknown');
            $table->unsignedBigInteger('cost')->unsigned();
            $table->unsignedBigInteger('massage_id')->unsigned();
            $table->timestamps();
        });
        Schema::table('packages' , function (Blueprint $table) {
            $table->foreign('massage_id')->references('id')->on('massages')->onDelete('cascade');
        });
        Schema::table('orders' , function (BluePrint $table) {
            $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('packages');
    }
}
