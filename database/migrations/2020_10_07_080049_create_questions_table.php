<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->unsignedBigInteger('question_type_id')->unsigned();
            $table->unsignedBigInteger('form_id')->unsigned();
            $table->timestamps();
        });
        Schema::table('questions' ,  function (BluePrint $table){
//            $table->foreign('question_type_id')->references('id')->on('question_types');
            $table->foreign('form_id')->references('id')->on('forms');
        });
        Schema::table('filled_questions' , function (Blueprint $table) {
            $table->foreign('question_id')->references('id')->on('questions')->onDelete('cascade');
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
        Schema::dropIfExists('questions');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
