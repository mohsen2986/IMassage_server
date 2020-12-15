<?php

use App\AboutUs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// packages
Route::resource('packages' , 'Packages\PackagesController' , ['only' => ['index' , 'store' ,'show' ,  'update' , 'destroy']]);
Route::resource('questionType' , 'Question\QuestionTypeController' , ['only' => ['index' , 'store' , 'destroy']]);
Route::resource('questions' , 'Question\QuestionConfig' , ['only' => ['index' , 'store' ]]);
Route::resource('question' , 'Question\QuestionController' , ['only' => ['index' , 'store' ,'show' ,  'update' , 'destroy']]);
Route::resource('forms' , 'Form\FormController' , ['only' => ['index' , 'store' ,'show' ,  'update' , 'destroy']]);
Route::resource('filledForm' , 'Form\FilledFormController' , ['only' => ['index' , 'store' ,'show']]);
Route::resource('fillQuestion' , 'Question\FilledQustionController' );

Route::resource('configs' , 'Config\ConfigController', ['only' => ['index' , 'update']] );


Route::get('configs' , 'Question\QuestionConfig@test');
// login user
Route::resource('login' , 'Auth\LoginController' , ['only' => ['store']]);
Route::resource('loginVerify' , 'Auth\LoginVerificationController' , ['only' => ['store']]);
Route::post('refresh' , 'Auth\LoginController@refresh');
Route::post('logout' , 'Auth\LoginController@logout');
// register user
Route::resource('register' , 'Auth\RegisterController' , ['only' => ['store']]);
Route::resource('registerVerify' , 'Auth\RegisterVerificationController' , ['only' => ['store']]);

// AboutUs
Route::resource('aboutUs' , 'AboutUs\AboutUsController' , ['only' => ['index' , 'store' , 'update' , 'destroy']]);
// offer
Route::resource('offer' , 'Offer\OfferController' , ['only' => ['index' , 'store' , 'destroy']]);
// Massage
Route::resource('massage' , 'Massage\MassageController' , ['only' => ['index' , 'store' , 'show' , 'update' , 'destroy' ]]);
// USERS
Route::resource('users_' , 'User\UserController' , ['only' => ['index' , 'store' , 'show' , 'update' , 'destroy' ]]);

// get answers
Route::post('answers' , 'Question\FilledQustionController@getAnswerQuestions');

// Auth
Route::middleware('auth:api')->group(function (){
    // logout  // todo test
//    Route::post('logout' , 'Login\LoginController@logout');

    // order
//    Route::resource('order' , 'Order\OrderController' , ['only' => ['index' , 'store']]);

    // main page
    Route::post('mainPage' , 'MainPage\MainPageController@mainPageInformation');
    // Boarder
    Route::resource('boarder' , 'Boarder\BoarderController' , ['only' => ['index' , 'store' , 'show' , 'update' , 'destroy']]);

});
Route::resource('transaction' , 'Transactions\TransactionsController' , ['only' => ['store' , 'index' , 'show']]);
Route::post('transaction_offer' , 'Transactions\TransactionsController@test');
Route::post('payTransaction' , 'Transactions\TransactionsController@payTransaction');
//Route::resource('transaction.' , 'Transactions\TransactionsController' , ['only' => ['test']]);
Route::resource('order' , 'Order\OrderController' , ['only' => ['index' ,'store']]);
Route::post('orders' , 'Order\OrderController@order' );  // todo this
Route::get('orders_history' , 'Order\OrderController@allOrderHistory' );  // todo this
Route::post('check_reserve_time' , 'Order\OrderController@checkTime');

Route::post('reserve' , 'ReservedTimeDates\ReservedTimeDatesController@reserve'); // todo reserve
Route::post('check_reserve_time' , 'ReservedTimeDates\ReservedTimeDatesController@checkTimes');

Route::resource('reservedTimeDates' , 'ReservedTimeDates\ReservedTimeDatesController' );

Route::get('available_dates' , 'ReservedTimeDates\ReservedTimeDatesController@getTimes' );
Route::post('available_date_time' , 'ReservedTimeDates\ReservedTimeDatesController@getAvailableTimeDate' );

Route::resource('user_info' , 'User\UserController' , ['only' => ['update']]);
Route::get('user_info' , 'User\UserController@getUserInformation' );

Route::resource('reg' , 'Auth\ResetPasswrdController');

Route::post('resetPassword' , 'Auth\ResetPasswordController@resetPassword');
Route::post('verifyResetPassword' , 'Auth\ResetPasswordController@resetPasswordSmsVerification');

Route::get('reservedOrders' , 'Order\OrderController@reservedOrders');

Route::resource('consulting' , 'Consulting\consultingController');

Route::get('getPdf' , 'User\UserController@downloadUsersAsPdf');

Route::get('test' , 'Config\ConfigController@test');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
