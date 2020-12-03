<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\SendSms;
use App\SmsToken;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

class ResetPasswordController extends Controller
{

    public function resetPassword(Request $request){
        $rules =[
            'phone' => 'required'
        ];
        $this->validate($request , $rules);

        if($user = User::where('phone' , '=' , $request['phone'])->first()) {
                $smsSender = new SendSms();
                $tokenData['code'] = SmsToken::generateCode();
                $tokenData['user_id'] = $user->id;
                $tokenData['token'] = SmsToken::generateToken();
                // send sms/
                $callback = $smsSender->sendSmsToNumber(array("+98" . $request['phone']), array("verification_code" => $tokenData['code']));
                // callback contain sms code
                $tokenData['sms_code'] = $callback;
                // save the sms token
                $smsToken = SmsToken::create($tokenData);

                $response = [
                    "message" => "sms send to the number",
                    "token" => $tokenData['token']
                ];
                return response()->json($response, 201);
        }else{
            $response = [
                "message" => "the phone is invalid" ,
            ];
            return response()->json($response , 201);
        }
    }

    public function resetPasswordSmsVerification(Request $request){
        $rules = [
            'token' => 'required' ,
            'code' => 'required' ,
            'password' => 'required | confirmed | min:8'
        ];
        $this->validate($request , $rules);

        if($smsToken = SmsToken::where('token' , '=' , $request['token'])->first()){
            if($smsToken->isValid()){
                if($smsToken->code === $request['code']){
                    $smsToken->used = SmsToken::USED;
                    $smsToken->save();
                    // get user and VERIFIED USER
                    $user = $smsToken->user;
                    $user->verified = User::VERIFIED_USER;
                    $user->save();
                    // reset password
                    $user->password = bcrypt(request('password'));
                    $user->save();

                    $response = [
                        "status" => "password successfully reset"
                    ];
                    return response()->json($response , 200);
                } // code invalid
                else{
                    $response = [
                        "status" => "code is invalid"
                    ];
                    return response()->json($response , 202);
                }
            } // toke or code is expired or used
            else {
                // token time out
                if($smsToken->isExpired()){
                    $response = [
                        "status" => "code is time out!!"
                    ];
                    return response()->json($response, 202);
                }
                $response = [
                    "status" => "code is expired"
                ];
                return response()->json($response, 206);
            }
        }else{
            $response = [
                "status" => "token is invalid"
            ];
            return response()->json($response, 209);
        }
    }

    public function checkCode(Request $request)
    {
        $rules = [
            'token' => 'required',
            'code' => 'required',
        ];
        $this->validate($request, $rules);

        if ($smsToken = SmsToken::where('token', '=', $request['token'])->first()) {
            if ($smsToken->isValid()) {
                if ($smsToken->code === $request['code']) {
                    $smsToken->used = SmsToken::USED;
                    $response = [
                        "status" => "password successfully reset"
                    ];
                    return response()->json($response , 200);
                }else{
                    $response = [
                        "status" => "code is invalid"
                    ];
                    return response()->json($response , 202);
                }
            }else{
                $response = [
                    "status" => "password successfully reset"
                ];
                return response()->json($response , 202);
            }
        }
    }
}
