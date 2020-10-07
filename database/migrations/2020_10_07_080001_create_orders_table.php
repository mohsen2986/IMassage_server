<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unsigned();
            $table->unsignedBigInteger('reserved_time_dated_id')->unsigned();
            $table->unsignedBigInteger('massage_id')->unsigned();
            $table->unsignedBigInteger('package_id')->unsigned();
//            $table->unsignedBigInteger('offer_id')->unsigned()->nullable();  // todo check this
            $table->unsignedBigInteger('transactions_id')->unsigned();
            $table->timestamps();
        });
        Schema::table('orders' , function (BluePrint $table) {
            $table->foreign('reserved_time_date_id')->references('id')->on('reserved_time_dates');
            $table->foreign('massage_id')->references('id')->on('massages');
            $table->foreign('package_id')->references('id')->on('packages');
            $table->foreign('transactions_id')->references('id')->on('transactions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
