<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilledQuesionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('filled_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('filled_form_id')->unsigned();
            $table->unsignedBigInteger('question_id')->unsigned();
            $table->timestamps();
        });
        Schema::table('filled_questions' , function (Blueprint $table) {
            $table->foreign('filled_form_id')->references('id')->on('filled_forms');
//            $table->foreign('question_id')->references('id')->on('questions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('filled_quesions');
    }
}
