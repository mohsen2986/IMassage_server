<?php

namespace App\Http\Controllers\AboutUs;

use App\AboutUs;
use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class AboutUsController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        return $this->showAll(AboutUs::all());
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
            'description' => 'required',
            'image' => 'required|image'
        ];
        $this->validate($request , $rules);

        // save data
        $data = request()->all();
        // sort image
        $data['image'] = request('image')->store('');
        // store data
        $product = AboutUs::create($data);

        return $this->showOne($product);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  AboutUs $aboutUs
     * @return JsonResponse
     */
    public function update(Request $request, AboutUs $aboutUs)
    {
        $aboutUs->fill($request->only([
            'description' ,
            'image' ,
        ]));
        // check has image
        if($request->hasFile('image')){
            // delete previous image
            Storage::delete($aboutUs->image);
            // store new image
            $aboutUs->image = request('image')->store('');
        }
        if($aboutUs->isClean()){
            return $this->errorResponse('you need to specify different value' , 422);
        }
        $aboutUs->save();
        return $this->showOne($aboutUs);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param AboutUs $aboutUs
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(AboutUs $aboutUs)
    {
        dd($aboutUs->description);
        // delete the massage from the database
        $aboutUs->delete();
        // delete image
        Storage::delete($aboutUs->image);

        return $this->showOne($aboutUs);
    }
}
