<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReservedTimeDates extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'data' ,
        'h8' ,
        'h9' ,
        'h10' ,
        'h11' ,
        'h12' ,
        'h13' ,
        'h14' ,
        'h15' ,
        'h16' ,
        'h17' ,
        'h19' ,
        'h20' ,
        'h21' ,
        'h22' ,
    ];
    public function order(){
        return $this->hasMany(Order::class);
    }
}
