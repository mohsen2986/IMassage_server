<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @package App
 * @property mixed ref_id
 * @property mixed amount
 * @property mixed is_used
 * @property mixed user_id
 * @property mixed valid_transaction
 */
class Transactions extends Model
{
    const IS_USED = 'IS_USED';
    const IS_NOT_USED = 'IS_NOT_USED';

    const VALID = 'VALID';
    const INVALID = 'INVALID';
    /**
     * @var array
     */
    protected $fillable = [
        'ref_id' ,
        'amount' ,
        'is_used' ,
        'user_id' ,
        'valid_transaction'
    ];
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function order(){
        return $this->belongsTo(Order::class);
    }
}
