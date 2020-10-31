<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\SendSms;
use App\SmsToken;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Laravel\Passport\Client;

class LoginController extends Controller
{
    private $clinet;

    public function __construct(){
        $this->clinet = Client::find(2);
    }

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
                // send sms/
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
    // refresh token
    public function refresh(Request $request){
        $this->validate($request ,[
            'refresh_token' ,
        ]);

        $params = [
            'grant_type' => 'refresh_token' ,
            'client_id' => $this->clinet->id ,
            'client_secret' => $this->clinet->secret ,
            'username' => request('username') ,
            'password' => request('password') ,
            'scope' => '*'
        ];

        $request->request->add($params);

        $proxy = Request::create('oauth/token' , 'POST');

        return Route::dispatch($proxy);
    }

    // logout
    public function logout(Request $request){
        $accessToken = Auth::user()->token();

        DB::table('oauth_refresh_tokens')
            ->where('access_token_id' , $accessToken)
            ->update(['revoked' => true]);

        $accessToken->revoke();
        return response()->json([], 204);
    }
}
