<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Config
 * @package App
 */
class Config extends Model
{
    protected $fillable = [
        'form_id'
    ];

    // RELATIONS
    public function form(){
        return $this->belongsTo(Form::class);
    }
}
