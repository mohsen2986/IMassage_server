<?php

namespace App\Http\Controllers\Massage;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Massage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class MassageController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        return $this->showAll(Massage::all());
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
            'name' => 'required',
            'cost' => 'required',
            'length' => 'required',
            'image' => 'required',
        ];
        $this->validate($request , $rules);
        // store data
        $data = request()->all();
        // save the image
        $data['image'] = request('image')->store('');
        // store massage to database
        $massage = Massage::create($data);

        return $this->showOne($massage);
    }

    /**
     * Display the specified resource.
     *
     * @param  Massage $massage
     * @return JsonResponse
     */
    public function show(Massage $massage)
    {
        return $this->showOne($massage);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Massage $massage
     * @return JsonResponse
     */
    public function update(Request $request, Massage $massage)
    {
        $massage->fill($request->only([
        'name' ,
        'cost' ,
        'length' ,
        'image' ,
        ]));
        // check has image
        if($request->hasFile('image')){
            // delete previous image
            Storage::delete($massage->image);
            // store new image
            $massage->image  = request('image')->store('');
        }
        if($massage->isClean()){
            return $this->errorResponse('yout need to specify to diffrend value' , 422);
        }
        // save the new massage
        $massage->save();
        return $this->showOne($massage);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Massage $massage
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(Massage $massage)
    {
        // delete the massage from the database
        $massage->delete();
        // delete image
        Storage::delete($massage->image);

        return $this->showOne($massage);
    }
}
