<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Config
 * @package App
 */
class Config extends Model
{
    const OPEN = "OPEN";
    const CLOSED = "CLOSED";

    protected $fillable = [
        'form_id',
        'h8' ,
        'h8_gender' ,
        'h9' ,
        'h9_gender' ,
        'h10' ,
        'h10_gender' ,
        'h11' ,
        'h11_gender' ,
        'h12' ,
        'h12_gender' ,
        'h13' ,
        'h13_gender' ,
        'h14' ,
        'h14_gender' ,
        'h15' ,
        'h15_gender' ,
        'h16' ,
        'h16_gender' ,
        'h17' ,
        'h17_gender' ,
        'h18' ,
        'h18_gender' ,
        'h19' ,
        'h19_gender' ,
        'h20' ,
        'h20_gender' ,
        'h21' ,
        'h21_gender' ,
        'h22' ,
        'h22_gender' ,
        'd1',
        'd2',
        'd3',
        'd4',
        'd5',
        'd6',
        'd7',
        'closed_days' ,
        'open_days' ,
    ];

    // RELATIONS
    public function form(){
        return $this->belongsTo(Form::class);
    }
}
