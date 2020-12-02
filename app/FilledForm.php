<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class FilledForm
 * @package App
 * @property mixed user_id
 * @property mixed form_id
 * @property mixed date
 */
class FilledForm extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'user_id' ,
        'form_id' ,
        'date' ,
    ];
    // RELATIONS
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function FilledQuestions(){
        return $this->hasMany(FilledQuestion::class);
    }
    public function form(){
        return $this->belongsTo(Form::class);
    }

}
