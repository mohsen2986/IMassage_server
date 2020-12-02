<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Form
 * @package App
 * @property mixed name
 */

class Form extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'name' ,
    ];
    // RELATIONS
    public function question(){
        return $this->hasMany(Question::class);
    }
    public function filledForm(){
        return $this->hasMany(FilledForm::class);
    }
    public function config(){
        return $this->hasOne(Config::class);
    }
}
