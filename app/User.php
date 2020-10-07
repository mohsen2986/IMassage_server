<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
    const VERIFIED_USER = '1';
    const UNVERIFIED_USER = '0';

    const ADMIN_USER = 'true';
    const REGULAR_USER = 'false';

    const MALE_GENDER = 'true';
    const FEMALE_GENDER = 'false';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'family' ,
        'phone' ,
        'photo' ,
        'father_name' ,
        'melli_code' ,
        'shenasnameh_code' ,
        'born_location' ,
        'address' ,
        'verified' ,
        'verification_token' ,
        'gender' ,
        'admin' ,
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function orders(){
        return $this->hasMany(Order::class);
    }
    public function transaction(){
        return $this->hasMany(Transactions::class);
    }
    public function filledForm(){
        return $this->hasMany(FilledForm::class);
    }

}
