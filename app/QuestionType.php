<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuestionType extends Model
{
    /**
     * @var array
     */
    protected $fillable=[
        'type' ,
    ];

    public function question(){
        return $this->hasMany(Question::class);
    }
}
