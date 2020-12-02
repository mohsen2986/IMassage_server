<?php

use App\Offers;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->date('date');
            $table->string('validate')->default(Offers::VALIDATE);
            $table->unsignedBigInteger('offer')->unsigned();
            $table->unsignedBigInteger('massage_id')->unsigned();
            $table->date('start_date')->nullable();
            $table->date('expire_date')->nullable();
            $table->timestamps();
        });

        Schema::table('offers' , function( Blueprint $table){

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
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('offers');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
