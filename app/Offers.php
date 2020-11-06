<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Class Massage
 * @package App
 * @property mixed code
 * @property mixed date
 * @property mixed validate
 * @property mixed offer
 */
class Offers extends Model
{
    const VALIDATE = '1';
    const INVALIDATE = '0';

    /**
     * @var array
     */
    protected $fillable =[
        'code' ,
        'date' ,
        'validate' ,
        'offer' ,
    ];
    // relations
    public function order(){
        return $this->hasMany(Order::class);
    }
    public function usedOffers(){
        return $this->belongsTo(UsedOffers::class);
    }
    // functions
    public static function generateCode(){
        return Str::random(5);
    }
}
