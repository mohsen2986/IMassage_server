<?php

use App\Order;
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
            $table->unsignedBigInteger('reserved_time_date_id')->unsigned();
            $table->unsignedBigInteger('massage_id')->unsigned();
            $table->unsignedBigInteger('package_id')->unsigned();
            $table->unsignedBigInteger('transactions_id')->unsigned();
            $table->unsignedBigInteger('filled_form_id')->unsigned();
            $table->timestamps();
        });
        Schema::table('orders' , function (BluePrint $table) {
//            $table->foreign('reserved_time_date_id')->references('id')->on('reserved_time_dates');
            $table->foreign('massage_id')->references('id')->on('massages')->onDelete('cascade');
//            $table->foreign('package_id')->references('id')->on('packages');
//            $table->foreign('transactions_id')->references('id')->on('transactions');
//            $table->foreign('time_id')->references('id')->on('times')->onDelete('cascade');
            $table->foreign('filled_form_id')->references('id')->on('filled_forms')->onDelete('cascade');
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
