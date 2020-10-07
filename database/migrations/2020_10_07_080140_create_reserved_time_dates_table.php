<?php

use App\ReservedTimeDates;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
            $table->string('h8' )->default(ReservedTimeDates::FREE);
            $table->string('h9' )->default(ReservedTimeDates::FREE);
            $table->string('h10')->default(ReservedTimeDates::FREE);
            $table->string('h11')->default(ReservedTimeDates::FREE);
            $table->string('h12')->default(ReservedTimeDates::FREE);
            $table->string('h13')->default(ReservedTimeDates::FREE);
            $table->string('h14')->default(ReservedTimeDates::FREE);
            $table->string('h15')->default(ReservedTimeDates::FREE);
            $table->string('h16')->default(ReservedTimeDates::FREE);
            $table->string('h17')->default(ReservedTimeDates::FREE);
            $table->string('h19')->default(ReservedTimeDates::FREE);
            $table->string('h20')->default(ReservedTimeDates::FREE);
            $table->string('h21')->default(ReservedTimeDates::FREE);
            $table->string('h22')->default(ReservedTimeDates::FREE);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reserved_time_dates');
    }
}
