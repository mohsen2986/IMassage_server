<?php

namespace App\Http\Controllers\Packages;

use App\Http\Controllers\ApiController;
use App\Packages;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
            'image' => 'required',
            'cost' => 'required',
            'massage_id' => 'required',
        ];
        $this->validate($request , $rules);
        $newPackage = Packages::create($request->all());

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
        return $this->showOne($package);
    }
}
