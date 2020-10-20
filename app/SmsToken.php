<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @property mixed code
 * @property mixed token
 * @property mixed valid
 * @property mixed used
 * @property mixed created_at
 */
class SmsToken extends Model
{

    const EXPIRATION_TIME = 12 ; // minutes
    const USED = "used";
    const UNUSED = "unused";
    /**
     * @var array
     */
    protected $fillable = [
        'code' ,
        'user_id' ,
        'used' ,
        'token' ,
        'sms_code' ,
    ];
    // relations
    public function user(){
        return $this->belongsTo(User::class);
    }
    // functions
    public function isValid(){
        return !$this->isUsed() && !$this->isExpired();
    }
    public function isUsed(){
        return $this->used === self::USED;
    }
    public  function isExpired(){
        return $this->created_at->diffInMinutes(Carbon::now()) > static::EXPIRATION_TIME;
    }
    // static generate tokens functions
    public static function generateToken(){
        return Str::random(120);
    }
    // return a random number with 4 digits
    public static function generateCode(){
        return rand(999 , 10000);
    }
}
