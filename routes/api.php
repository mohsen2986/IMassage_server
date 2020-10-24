<?php

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

Route::resource('packages' , 'Packages\PackagesController' , ['except' => ['create' ,  'edit']]);
Route::resource('questionType' , 'Question\QuestionTypeController' , ['only' => ['index' , 'store' , 'destroy']]);
// login user
Route::resource('login' , 'Auth\LoginController' , ['only' => ['store']]);
Route::resource('loginVerify' , 'Auth\LoginVerificationController' , ['only' => ['store']]);
Route::post('refresh' , 'Auth\LoginController@refresh');
Route::post('logout' , 'Auth\LoginController@logout');
// register user
Route::resource('register' , 'Auth\RegisterController' , ['only' => ['store']]);
Route::resource('registerVerify' , 'Auth\RegisterVerificationController' , ['only' => ['store']]);
// main page
Route::post('mainPage' , 'MainPage\MainPageController@mainPageInformation');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
