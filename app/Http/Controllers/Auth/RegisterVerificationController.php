<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\SmsToken;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class RegisterVerificationController extends Controller
{

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
            'token' => 'required' ,
            'code' => 'required'
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

                    $response = [
                        "status" => "verified"
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
}
