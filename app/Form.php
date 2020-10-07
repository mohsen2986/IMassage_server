<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'name' ,
    ];
    public function question(){
        return $this->hasMany(Question::class);
    }
    public function filledForm(){
        return $this->hasMany(FilledForm::class);
    }
}
