<?php

namespace App;

use App\Transformers\OfferTransformer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Class Massage
 * @package App
 * @property mixed code
 * @property mixed date
 * @property mixed validate
 * @property mixed offer
 */
class Offers extends Model
{
    public $transformer = OfferTransformer::class;

    const VALIDATE = '1';
    const INVALIDATE = '0';

    /**
     * @var array
     */
    protected $fillable =[
        'code' ,
        'date' ,
        'validate' ,
        'offer' ,
        'massage_id' ,
        'start_date' ,
        'expire_date' ,
    ];
    // relations
    public function order(){
        return $this->hasMany(Order::class);
    }
    public function usedOffers(){
        return $this->belongsTo(UsedOffers::class);
    }
    public function transactions(){
        return $this->belongsToMany(Transactions::class);
    }
    public function massage(){
        return $this->belongsTo(Massage::class);
    }
    // functions
    public static function generateCode(){
        return Str::random(5);
    }
}
