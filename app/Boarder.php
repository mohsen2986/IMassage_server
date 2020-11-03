<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Boarder
 * @package App
 * @property mixed title
 * @property mixed image
 * @property mixed description
 *
 */

class Boarder extends Model
{
    /**
     *@var array
     */
    protected $fillable = [
        'title' ,
        'image' ,
        'description'
    ];

}
