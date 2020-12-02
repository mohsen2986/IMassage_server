<?php

use App\ReservedTimeDates;
use App\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateReservedTimeDatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reserved_time_dates', function (Blueprint $table) {
            $table->id();
            $table->date('date');
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
            $table->timestamps();
        });
        Schema::table('orders' , function (BluePrint $table) {
            $table->foreign('reserved_time_date_id')->references('id')->on('reserved_time_dates')->onDelete('cascade');
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
        Schema::dropIfExists('reserved_time_dates');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
