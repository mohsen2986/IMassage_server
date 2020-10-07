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
            $table->string('description');
            $table->string('image');
            $table->integer('cost')->unsigned();
            $table->integer('massage_id')->unsigned();
            $table->timestamps();
        });
        Schema::table('packages' , function (Blueprint $table) {
            $table->foreign('massage_id')->references('id')->on('massages');
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
