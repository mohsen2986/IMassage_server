<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Time extends Model
{
    const H8 =  'H8';
    const H9 =  'H9';
    const H10 = 'H10';
    const H11 = 'H11';
    const H12 = 'H12';
    const H13 = 'H13';
    const H14 = 'H14';
    const H15 = 'H15';
    const H16 = 'H16';
    const H17 = 'H17';
    const H18 = 'H18';
    const H19 = 'H19';
    const H20 = 'H20';
    const H21 = 'H21';
    const H22 = 'H22';
    /**
     * @var array
     */
    protected $fillable = [
        'time' ,
        'order_id'
    ];

    // relations
    public function order(){
        return $this->belongsTo(Order::class);
    }
}
