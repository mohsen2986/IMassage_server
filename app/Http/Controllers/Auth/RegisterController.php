<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\SendSms;
use App\SmsToken;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    // todo user regex for validating gender MALE and FEMALE
    // todo set max for requests
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'password' => 'required | confirmed | min:8' ,
            'family' => 'required',
            'phone' => 'required | min:10 | max:10 | unique:users',
            'gender' => 'required',
            'photo' ,
        ];
        $this->validate($request , $rules);
        // get and maintain the request
        $data = $request->all();
        $data['password'] = bcrypt(request('password'));
        // add user to DB
        $user = User::create($data);
        if(request('photo')){
            $user->phone = request('photo')->store('');
            $user->save();
        }
        // send sms and verification token
        $smsSender = new SendSms();
        $tokenData['code'] = SmsToken::generateCode();
        $tokenData['user_id'] = $user->id;
        $tokenData['used'] = SmsToken::UNUSED;
        $tokenData['token'] = SmsToken::generateToken();
        // send sms
        $callback = $smsSender->sendSmsToNumber(array("+98" . $data['phone']) , array("verification_code" => $tokenData['code']));
        // callback contain sms code
        $tokenData['sms_code'] = $callback;
        // save the sms token
        $smsToken = SmsToken::create($tokenData);

        $response = [
            "message" => "sms send to the number" ,
            "token" => $tokenData['token']
        ];
        return response()->json($response , 201);
    }


}
