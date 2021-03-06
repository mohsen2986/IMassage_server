<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Massage
 * @package App
 * @property mixed name
 * @property mixed cost
 * @property mixed image
 */
class Massage extends Model
{
    /**
     * @var array
     **/
    protected $fillable = [
        'name' ,
        'cost' ,
        'image' ,
        'description' ,
    ];
    // RELATIONS
    public function package(){
        return $this->hasMany(Packages::class);
    }
    public function order(){
        return $this->hasMany(Order::class);
    }
    public function offers(){
        return $this->hasMany(Offers::class);
    }
}
