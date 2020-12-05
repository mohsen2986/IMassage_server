<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class OffersTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offers_transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('offers_id')->unsigned();
            $table->unsignedBigInteger('transactions_id')->unsigned();
        });

        Schema::table('offers_transactions' , function (Blueprint $table){
            $table->foreign('offers_id')->references('id')->on('offers')->onDelete('cascade');
            $table->foreign('transactions_id')->references('id')->on('transactions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offers_transactions');
    }
}
