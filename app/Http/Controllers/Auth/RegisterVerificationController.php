<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\SmsToken;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use Laravel\Passport\Client;

class RegisterVerificationController extends Controller
{
    private $client;
    public function __construct(){
        $this->client = Client::find(2);
    }

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
            'code' => 'required' ,
            'password' => 'required' ,
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


//                    $response = [
//                        "status" => "verified"
//                    ];
//                    return response()->json($response , 200);
                    // passport service
                    $params = [
                        'grant_type' => 'password' ,
                        'client_id' => $this->client->id ,
                        'client_secret' => $this->client->secret ,
                        'username' => $user->phone ,
                        'password' => request('password') ,
                        'scope' => '*'
                    ];
                    $request->request->add($params);
                    $proxy = Request::create('oauth/token' , 'POST');
                    return Route::dispatch($proxy);

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
