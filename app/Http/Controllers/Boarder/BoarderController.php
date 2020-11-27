<?php

namespace App\Http\Controllers\Boarder;

use App\Boarder;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use PhpParser\JsonDecoder;
use Psy\Util\Json;

class BoarderController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        return $this->showAll(Boarder::all());
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
            'title' => 'required' ,
            'image' => 'required|image' ,
            'description' => 'required'
        ];
        $this->validate($request , $rules);
        // save data
        $data = request()->all();
        // store data
        $data['image'] = request('image')->store('');
        // store data
        $boarder = Boarder::create($data);

        return $this->showOne($boarder);
    }

    /**
     * Display the specified resource.
     *
     * @param Boarder $boarder
     * @return JsonResponse
     */
    public function show(Boarder $boarder)
    {
        return $this->showOne($boarder);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  Boarder $boarder
     * @return JsonResponse
     */
    public function update(Request $request, Boarder $boarder)
    {
        $boarder->fill($request->only([
            'title',
            'description',
        ]));
        if($request->hasFile('image')){
            // delete previous image
            Storage::delete($boarder->image);
            // save new image
            $boarder->image = request('image')->store('');
        }
        if($boarder->isClean()){
            return $this->errorResponse('you need to specify a different value to update' , 422);
        }
        // save the boarder
        $boarder->save();
        return $this->showOne($boarder);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Boarder $boarder
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(Boarder $boarder)
    {
        $boarder->delete();
        // delete image
        Storage::delete($boarder->image);

        return $this->showOne($boarder);
    }
}
