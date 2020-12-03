<?php

namespace App\Http\Controllers\Consulting;

use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ApiController;

class consultingController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $users = DB::table('consulting')->
        where('is_consulting' , '=' , User::DO_NOT_NEED_CONSULTING);

        return $this->showAll($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        // get the user is form auth
        $user = Auth::user();
        // create n new row
        DB::table('consulting')->insert([
            'user_id' => $user->id ,
            'is_consulting' => User::NEED_CONSULTING ,
        ]);
        $response = [
            "status" => "saved"
        ];
        return response()->json($response , 202);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
