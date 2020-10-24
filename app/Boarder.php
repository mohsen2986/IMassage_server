<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Boarder extends Model
{
    /**
     *@var array
     */
    protected $fillable = [
        'title' ,
        'photo' ,
        'description'
    ];

}
