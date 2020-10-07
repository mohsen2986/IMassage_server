<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('family')->nullable();
            $table->string('photo')->nullable();
            $table->string('phone')->nullable();
            $table->string('father_name')->nullable();
            $table->string('melli_code')->nullable();
            $table->string('shenasnameh_code')->nullable();
            $table->string('born_location')->nullable();
            $table->string('address')->nullable();
            $table->string('verified')->nullable();
            $table->string('verification_token')->nullable();
            $table->string('gender')->nullable();
            $table->string('admin');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
