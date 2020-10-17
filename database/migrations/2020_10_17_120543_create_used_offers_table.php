<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsedOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('used_offers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->unsigned();
            $table->unsignedBigInteger('offer_id')->unsigned();
            $table->string('date');
            $table->timestamps();
        });
        Schema::table('used_offers' , function (Blueprint $table){
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('offer_id')->references('id')->on('offers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('used_offers');
    }
}
