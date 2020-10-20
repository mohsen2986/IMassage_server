<?php

use App\SmsToken;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmsTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('used')->default(SmsToken::UNUSED);
            $table->unsignedBigInteger('user_id')->unsigned();
            $table->string('sms_code');
            $table->string('token');
            $table->timestamps();
        });
        Schema::table('sms_tokens' , function (Blueprint $table){
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sms_tokens');
    }
}
