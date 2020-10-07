<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Massage extends Model
{
    /**
     * @var array
     **/
    protected $fillable = [
        'name' ,
        'cost' ,
        'cost' ,
        'length' ,
        'image' ,
    ];
    public function package(){
        return $this->hasMany(Packages::class);
    }
    public function order(){
        return $this->hasMany(Order::class);
    }
}
