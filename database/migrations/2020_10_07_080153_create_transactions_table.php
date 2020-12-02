<?php

use App\Transactions;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('amount')->unsigned();
            $table->unsignedBigInteger('amount_with_offer')->unsigned();
            $table->unsignedBigInteger('user_id')->unsigned();
            $table->string('ref_id');
            $table->string('is_used')->default(Transactions::IS_NOT_USED);
            $table->string('valid_transaction')->default(Transactions::INVALID);
            $table->timestamps();
        });
        Schema::table('transactions' , function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onDelete('cascade');
        });
        Schema::table('orders' , function (BluePrint $table) {
            $table->foreign('transactions_id')->references('id')->on('transactions')->onDelete('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('orders');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
