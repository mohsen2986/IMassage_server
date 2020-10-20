<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\SendSms;
use App\SmsToken;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    // todo remember that invalid previous sms tokens if user resend

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
            'password'=> 'required',
            'phone' => 'required',
        ];
        $this->validate($request , $rules);

        if($user = User::where('phone' , '=' , $request['phone'])->first()){
            if(Hash::check( $request['password'] , $user->password)) {
                $smsSender = new SendSms();
                $tokenData['code'] = SmsToken::generateCode();
                $tokenData['user_id'] = $user->id;
                $tokenData['token'] = SmsToken::generateToken();
                // send sms
                $callback = $smsSender->sendSmsToNumber(array("+98" . $request['phone']) , array("verification_code" => $tokenData['code']));
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
            // if the password was incorrect
            $response = [
                "message" => "username or password are not valid" ,
            ];
            return response()->json($response , 201);
        }
        else{
            // if the user dosent exit PHONE NUMBER
            $response = [
                "message" => "username or password are not valid" ,
            ];
            return response()->json($response , 201);
        }
    }
}
