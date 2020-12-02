<?php

namespace App;

use App\Transformers\OrderTransformer;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Order
 * @package App
 * @property mixed user_id
 * @property mixed reserved_time_date_id
 * @property mixed massage_id
 * @property mixed package_id
 * @property mixed offer
 * @property mixed transactions_id
 */
class Order extends Model
{
    public $transformer = OrderTransformer::class;

    const OFFER_USED = '1';
    const UNUSED_OFFER = '0';
    /**
     * @var array
     */
    protected $fillable =[
        'user_id' ,
        'reserved_time_date_id' ,
        'massage_id' ,
        'package_id' ,
        'transactions_id',
        'filled_form_id'
    ];
    // Relations
    public function reservedTimeDates(){
        return $this->belongsTo(ReservedTimeDates::class , 'reserved_time_date_id' , 'id');
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function massage(){
        return $this->belongsTo(Massage::class);
    }
    public function packages(){
        return $this->belongsTo(Packages::class , 'package_id' , 'id');
    }
    public function offer(){
        return $this->belongsTo(Offers::class);
    }
    public function transactions(){
        return $this->belongsTo(Transactions::class);
    }
    public function usedOffers(){
        return $this->belongsTo(UsedOffers::class);
    }
    public function times(){
        return $this->hasMany(Time::class);
    }
    public function filledForm(){
        return $this->belongsTo(FilledForm::class);
    }
}
