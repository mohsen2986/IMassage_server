<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsedOffers extends Model
{
 /*
  * @var array
  */
 protected $fillable = [
     'order_id' ,
     'offer_id' ,
     'date'
 ];
 public function user(){
     return $this->hasOne(User::class);
 }
 public function offer(){
     return $this->hasOne(Offers::class);
 }
}
