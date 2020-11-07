<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Question
 * @package App
 * @property mixed question
 * @property mixed question_type_id
 */
class Question extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'question' ,
        'question_type_id' ,
        'form_id' ,
    ];
    // RELATIONS
    public function questionType(){
        return $this->hasOne(QuestionType::class);
    }
    public function filledQuestion(){
        return $this->hasMany(FilledQuestion::class);
    }
    public function form(){
        return $this->belongsTo(Form::class);
    }
}
