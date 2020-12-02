<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class filledQuestion
 * @package App
 * @property mixed filled_form_id
 * @property mixed question_id
 */
class filledQuestion extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'filled_form_id' ,
        'question_id' ,
        'answer' ,
    ];
    // RELATIONS
    public function question(){
        return $this->belongsTo(Question::class);
    }
    public function FilledForm(){
        return $this->belongsTo(FilledForm::class);
    }
}
