<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class filledQuestion extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'filled_form_id' ,
        'question_id' ,
    ];
    public function question(){
        return $this->belongsTo(Question::class);
    }
    public function FilledForm(){
        return $this->belongsTo(FilledForm::class);
    }
}
