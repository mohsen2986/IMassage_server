<?php

namespace App\Http\Controllers\Packages;

use App\Http\Controllers\ApiController;
use App\Massage;
use App\Packages;
use App\TimeConfig;
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
        foreach ($packages as $package){
            $package['massage_id'] = $package->massage;

            if(count($package->timeConfigs)){
                $package['have_time_configs'] = 'true';
            }else{
                $package['have_time_configs'] = 'false';
            }
        }
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
            'length' => 'required' ,
        ];
        $this->validate($request, $rules);

        $massage = Massage::find(request('massage_id'));
        // check massage exist
        if($massage){
        // store data
        $data = request()->all();
        // save image
        $data['image'] = request('image')->store('');
        // calculate the cost
        $data['cost'] = ($massage->cost + request('cost'));
        // store package to the database
        $newPackage = Packages::create($data);

        return $this->showOne($newPackage);
        }
        // the massage not valid
        return $this->errorResponse('the massage is not valid' , 422);
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
        ]));
        if(request('cost')){
            $massage = $package->massage;
            $package->cost = ($massage->cost + request('cost'));
        }
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
    /*
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
    public function createTimeConfigForPackage(Request $request){
        $rules =[
            'package' => 'required'
        ];
        $this->validate($request , $rules);
        $package = Packages::find(request('package'));

        if(!count($package->timeConfigs)){
            $timeConfig = TimeConfig::create();
            $package->timeConfigs()->sync($timeConfig->id);
            return $this->showOne($timeConfig);
        }
        return $this->showOne($package->timeConfigs[0]);
    }
    public function setTimeConfigForPackage(Request $request){
        $rules = [
            'package' => 'required'
        ];
        $this->validate($request , $rules);
        $package  = Packages::find(request('package'));

        if(count($package->timeConfigs)){
            $timeConfig= $package->timeConfigs;

            $timeConfig[0]->fill( $request->only([
                'h1' ,
                'h1_gender' ,
                'h2' ,
                'h2_gender' ,
                'h3' ,
                'h3_gender' ,
                'h4' ,
                'h4_gender' ,
                'h5' ,
                'h5_gender' ,
                'h6' ,
                'h6_gender' ,
                'h7' ,
                'h7_gender' ,
                'h8' ,
                'h8_gender' ,
                'h9' ,
                'h9_gender' ,
                'h10' ,
                'h10_gender' ,
                'h11' ,
                'h11_gender' ,
                'h12' ,
                'h12_gender' ,
                'h13' ,
                'h13_gender' ,
                'h14' ,
                'h14_gender' ,
                'h15' ,
                'h15_gender' ,
                'h16' ,
                'h16_gender' ,
                'h17' ,
                'h17_gender' ,
                'h18' ,
                'h18_gender' ,
                'h19' ,
                'h19_gender' ,
                'h20' ,
                'h20_gender' ,
                'h21' ,
                'h21_gender' ,
                'h22' ,
                'h22_gender' ,
                'h23' ,
                'h23_gender' ,
                'h24' ,
                'h24_gender' ,
            ]));
            $timeConfig[0]->save();
            return $this->showOne($timeConfig[0]);

        }
    }
}
