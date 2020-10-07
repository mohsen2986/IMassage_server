<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuesitonType extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'question' ,
        'question_type_id'
    ];
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
