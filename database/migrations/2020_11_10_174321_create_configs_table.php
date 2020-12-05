<?php

use App\Config;
use App\ReservedTimeDates;
use App\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('form_id')->unsigned();
            $table->string('h1')->default(ReservedTimeDates::FREE);
            $table->string('h1_gender')->default(User::MALE_GENDER);
            $table->string('h2')->default(ReservedTimeDates::FREE);
            $table->string('h2_gender')->default(User::MALE_GENDER);
            $table->string('h3')->default(ReservedTimeDates::FREE);
            $table->string('h3_gender')->default(User::MALE_GENDER);
            $table->string('h4')->default(ReservedTimeDates::FREE);
            $table->string('h4_gender')->default(User::MALE_GENDER);
            $table->string('h5')->default(ReservedTimeDates::FREE);
            $table->string('h5_gender')->default(User::MALE_GENDER);
            $table->string('h6')->default(ReservedTimeDates::FREE);
            $table->string('h6_gender')->default(User::MALE_GENDER);
            $table->string('h7')->default(ReservedTimeDates::FREE);
            $table->string('h7_gender')->default(User::MALE_GENDER);
            $table->string('h8')->default(ReservedTimeDates::FREE);
            $table->string('h8_gender')->default(User::MALE_GENDER);
            $table->string('h9')->default(ReservedTimeDates::FREE);
            $table->string('h9_gender')->default(User::MALE_GENDER);
            $table->string('h10')->default(ReservedTimeDates::FREE);
            $table->string('h10_gender')->default(user::MALE_GENDER);
            $table->string('h11')->default(ReservedTimeDates::FREE);
            $table->string('h11_gender')->default(User::MALE_GENDER);
            $table->string('h12')->default(ReservedTimeDates::FREE);
            $table->string('h12_gender')->default(User::MALE_GENDER);
            $table->string('h13')->default(ReservedTimeDates::FREE);
            $table->string('h13_gender')->default(User::MALE_GENDER);
            $table->string('h14')->default(ReservedTimeDates::FREE);
            $table->string('h14_gender')->default(User::MALE_GENDER);
            $table->string('h15')->default(ReservedTimeDates::FREE);
            $table->string('h15_gender')->default(User::MALE_GENDER);
            $table->string('h16')->default(ReservedTimeDates::FREE);
            $table->string('h16_gender')->default(User::MALE_GENDER);
            $table->string('h17')->default(ReservedTimeDates::FREE);
            $table->string('h17_gender')->default(User::MALE_GENDER);
            $table->string('h18')->default(ReservedTimeDates::FREE);
            $table->string('h18_gender')->default(User::MALE_GENDER);
            $table->string('h19')->default(ReservedTimeDates::FREE);
            $table->string('h19_gender')->default(User::MALE_GENDER);
            $table->string('h20')->default(ReservedTimeDates::FREE);
            $table->string('h20_gender')->default(User::MALE_GENDER);
            $table->string('h21')->default(ReservedTimeDates::FREE);
            $table->string('h21_gender')->default(User::MALE_GENDER);
            $table->string('h22')->default(ReservedTimeDates::FREE);
            $table->string('h22_gender')->default(User::MALE_GENDER);
            $table->string('h23')->default(ReservedTimeDates::FREE);
            $table->string('h23_gender')->default(User::MALE_GENDER);
            $table->string('h24')->default(ReservedTimeDates::FREE);
            $table->string('h24_gender')->default(User::MALE_GENDER);

            $table->string('d1')->default(Config::OPEN);
            $table->string('d2')->default(Config::OPEN);
            $table->string('d3')->default(Config::OPEN);
            $table->string('d4')->default(Config::OPEN);
            $table->string('d5')->default(Config::OPEN);
            $table->string('d6')->default(Config::OPEN);
            $table->string('d7')->default(Config::OPEN);

            $table->integer('closed_days')->default(0);
            $table->integer('open_days')->default(0);

            $table->timestamps();
        });

        Schema::table('configs' , function( Blueprint $table){
            $table->foreign('form_id')->references('id')->on('forms')->onDelete('cascade');
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
        Schema::dropIfExists('configs');
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
    }
}
