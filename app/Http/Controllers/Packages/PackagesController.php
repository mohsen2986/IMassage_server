<?php

namespace App\Http\Controllers\Packages;

use App\Http\Controllers\ApiController;
use App\Packages;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class PackagesController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $packages = Packages::all();
        return $this->showAll($packages);
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
        $rules =[
            'name' => 'required',
            'description' => 'required',
            'image' => 'required|image',
            'cost' => 'required',
            'massage_id' => 'required',
        ];
        $this->validate($request , $rules);
        // store data
        $data = request()->all();
        // save image
        $data['image'] = request('image')->store('');
        // store package to the database
        $newPackage = Packages::create($data);

        return $this->showOne($newPackage);
    }

    /**
     * Display the specified resource.
     *
     * @param Packages $package
     * @return JsonResponse
     */
    public function show(Packages $package)
    {
        return $this->showOne($package);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Packages $package
     * @return JsonResponse
     */
    public function update(Request $request, Packages $package)
    {
        $package->fill($request->only([
            'name' ,
            'description' ,
            'image' ,
            'cost' ,
            'massage_id' ,
        ]));
        if($request->hasFile('image')){
            // delete previous image
            Storage::delete($package->image);
            // store new image
            $package->image = request('image')->store('');
        }
        if($package->isClean()){
            return $this->errorResponse('You need to specify any different value to update' , 442);
        }
        $package->save();

        return $this->showOne($package);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Packages $package
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(Packages $package)
    {
        $package->delete();
        // delete image from database
        Storage::delete($package->image);

        return $this->showOne($package);
    }
}
