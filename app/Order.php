<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /**
     * @var array
     */
    protected $fillable =[
        'time' ,
        'user_id' ,
        'reserved_time_dates_id' ,
        'massage_id' ,
        'package_id' ,
        'offer_id' ,
        'transactions_id',
    ];
    public function reservedTimeDates(){
        return $this->belongsTo(ReservedTimeDates::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function massage(){
        return $this->belongsTo(Massage::class);
    }
    public function packages(){
        return $this->belongsTo(Packages::class);
    }
    public function offer(){
        return $this->belongsTo(Offers::class);
    }
    public function transactions(){
        return $this->belongsTo(Transactions::class);
    }
}
