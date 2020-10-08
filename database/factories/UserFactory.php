<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\FilledForm;
use App\FilledQuestion;
use App\Form;
use App\Massage;
use App\Offers;
use App\Order;
use App\Packages;
use App\Question;
use App\QuestionType;
use App\ReservedTimeDates;
use App\Transactions;
use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

// user factory
$factory->define(User::class, function (Faker $faker) {
    static $password;
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => $password ?: $password = bcrypt('secret'), // password
        'remember_token' => Str::random(10),
        'family' => $faker->lastName ,
        'phone' => $faker->phoneNumber ,
        'father_name' => $faker->firstName ,
        'melli_code' => $faker->randomNumber(9) ,
        'shenasnameh_code' => $faker->randomNumber(9) ,
        'born_location'=> $faker->city ,
        'address' => $faker->address,
        'verified' => $verified = $faker->randomElement([User::VERIFIED_USER , USER::UNVERIFIED_USER]),
        'verification_token' => $verified == User::VERIFIED_USER ? null : User::generateToken(),
        'gender' => $faker->randomElement([User::MALE_GENDER , USER::FEMALE_GENDER]),
        'admin' => $faker->randomElement([USER::ADMIN_USER , USER::REGULAR_USER]),
    ];
});
// filled form factory
$factory->define(FilledForm::class , function (Faker $faker){
    return [
        'user_id' => User::all()->random()->id,
        'form_id'=> Form::all()->random()->id,
        'date' => $faker->dateTimeBetween('+0 days' , '+7 days'),
    ];
});
$factory->define(Form::class , function (Faker  $faker){
    return [
        'name' => $faker->randomLetter,
    ];
});
$factory->define(FilledQuestion::class , function (Faker $faker){
    return [
        'filled_form_id' => Form::all()->random()->id ,
        'question_id' => Question::all()->random()->id,
    ];
});

$factory->define(Massage::class , function (Faker $faker){
   return [
       'name' => $faker->name,
       'cost' => $faker->randomNumber(6),
       'length' => $faker->randomElement(['1' , '2' , '3']),
//       'image' , // todo image
   ];
});
$factory->define(Offers::class , function (Faker $faker){
   return [
       'code' => $faker->randomNumber(6),
       'date' => $faker->date(),
       'validate' => $faker->randomElement([Offers::VALIDATE , Offers::INVALIDATE]),
       'offer' => $faker->randomNumber(2),
   ];
});
$factory->define(Order::class , function (Faker $faker){
    return [
        'time' => $faker->time(),
        'user_id' => User::all()->random()->id,
        'reserved_time_date_id'=> ReservedTimeDates::all()->random()->id ,
        'massage_id' => Massage::all()->random()->id,
        'package_id' => Packages::all()->random()->id,
//        'offer_id' => Offers::all()->random()->id,
        'transactions_id' => Transactions::all()->random()->id,
    ];
});
$factory->define(Packages::class , function (Faker $faker){
   return [
       'name' => $faker->name,
       'description' => $faker->paragraph(1),
//       'image' , // todo image set
       'cost' => $faker->randomNumber(5),
       'massage_id' => Massage::all()->random()->id ,
   ];
});
$factory->define(QuestionType::class , function (Faker $faker){
    return [
        'type' => $faker->randomElement(['type_one' , 'type_two']),
    ];
});
$factory->define(Question::class , function (Faker $faker){
    return [
        'question' => $faker->randomLetter,
        'question_type_id' => QuestionType::all()->random()->id
    ];
});
$factory->define(ReservedTimeDates::class , function (Faker $faker){
   return [
       'date' => $faker->date(),
       'h8' => $faker->randomElement([ReservedTimeDates::RESERVED , ReservedTimeDates::FREE]),
       'h9' => $faker->randomElement([ReservedTimeDates::RESERVED , ReservedTimeDates::FREE]),
       'h10' => $faker->randomElement([ReservedTimeDates::RESERVED , ReservedTimeDates::FREE]),
       'h11' => $faker->randomElement([ReservedTimeDates::RESERVED , ReservedTimeDates::FREE]),
       'h12' => $faker->randomElement([ReservedTimeDates::RESERVED , ReservedTimeDates::FREE]),
       'h13' => $faker->randomElement([ReservedTimeDates::RESERVED , ReservedTimeDates::FREE]),
       'h14' => $faker->randomElement([ReservedTimeDates::RESERVED , ReservedTimeDates::FREE]),
       'h15' => $faker->randomElement([ReservedTimeDates::RESERVED , ReservedTimeDates::FREE]),
       'h16' => $faker->randomElement([ReservedTimeDates::RESERVED , ReservedTimeDates::FREE]),
       'h17' => $faker->randomElement([ReservedTimeDates::RESERVED , ReservedTimeDates::FREE]),
       'h19' => $faker->randomElement([ReservedTimeDates::RESERVED , ReservedTimeDates::FREE]),
       'h20' => $faker->randomElement([ReservedTimeDates::RESERVED , ReservedTimeDates::FREE]),
       'h21' => $faker->randomElement([ReservedTimeDates::RESERVED , ReservedTimeDates::FREE]),
       'h22' => $faker->randomElement([ReservedTimeDates::RESERVED , ReservedTimeDates::FREE]),
   ];
});
$factory->define(Transactions::class, function (Faker $faker){
   return [
       'amount' => $faker->randomNumber(5),
       'user_id' => User::all()->random()->id,
   ];
});
