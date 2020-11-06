<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class QuestionType
 * @package App
 * @property mixed type
 */
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
