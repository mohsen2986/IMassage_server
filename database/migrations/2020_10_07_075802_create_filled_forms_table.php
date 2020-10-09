<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilledFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('filled_forms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('form_id');
            $table->date('date');
            $table->timestamps();
        });

        Schema::table('filled_forms' , function (BluePrint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
//            $table->foreign('form_id')->references('id')->on('forms');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('filled_forms');
    }
}
