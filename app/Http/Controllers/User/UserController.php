<?php

namespace App\Http\Controllers\User;

use App\FilledForm;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\User;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\Client;
use niklasravnsborg\LaravelPdf\Facades\Pdf as PDF;
//inclide_once ('vendor/tcpdf-master/tcpdf.php');
require_once base_path('vendor/tcpdf-master/tcpdf.php');
require_once base_path('app/generatePdf.php');

class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $users = User::all();
        return $this->showAll($users , 200, true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse|Response
     */
    public function store(Request $request)
    {
        // get user from Auth lager
        $user = Auth::user();
        $user->fill(
            $request->only([
                'name' ,
                'family'
            ])
        );
        if($user->isClean()){
            return $this->errorResponse('you need to specify to different value' , 422);
        }
        // save new user info
        $user->save();

        return $this->showOne($user);
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return JsonResponse
     */
    public function show(User $user)
    {
        return $this->showOne($user);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request , $id)
    {
        // get user from Auth lager
        $user = Auth::user();
//        $user = User::find($user->id);
        $user->fill(
            $request->only([
                'name' ,
                'family' ,
                'gender' ,
                'photo' ,
                'address' ,
                'date' ,
            ])
        );
        // check has image
        if($request->hasFile('photo')){
            // delete previous image
            if($user->photo != Null)
                Storage::delete($user->photo);
            // store new image
            $user->photo  = request('photo')->store('');
        }

        if($user->isClean()){
            return $this->errorResponse('you need to specify to different value' , 422);
        }

        $user->save();
        // save new user info
        return $this->showOne($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    public function getUserInformation(){
        $user = Auth::user();
        $user = User::find($user->id , ['name' , 'family' , 'photo' , 'phone' , 'gender' , 'date' , 'address'] );
        return $this->showOne($user);
    }

    public function downloadUsersAsPdf(Request $request){
        $users = User::all();
        return generate($users);
    }

    public function checkFilledQuestions(){
        $user = Auth::user();
        $filledForm = FilledForm::where('user_id' , '=' , $user->id)->first();

        if ($filledForm){
            $response = [
                "status" => "form filled"
            ];
            return response()->json($response , 200);
        }
        $response = [
            "status" => "form not filled"
        ];
        return response()->json($response , 202);
    }

}
