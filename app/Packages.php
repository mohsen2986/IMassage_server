<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Packages extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'name' ,
        'description' ,
        'image' ,
        'cost' ,
        'massage_id'
    ];
    public function massage(){
        return $this->belongsTo(Massage::class);
    }
    public function Order(){
        return $this->hasMany(Order::class);
    }
}
