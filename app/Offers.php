<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
    public function order(){
        return $this->hasMany(Order::class);
    }
}
