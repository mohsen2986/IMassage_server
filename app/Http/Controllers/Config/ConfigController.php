<?php

namespace App\Http\Controllers\Config;

use App\Config;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class ConfigController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $config = Config::find(1);
        return $this->showONe($config);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $rules =[
            'h8' => 'required',
            'h8_gender' => 'required' ,
            'h9' => 'required' ,
            'h9_gender' => 'required',
            'h10' => 'required',
            'h10_gender' => 'required',
            'h11' => 'required',
            'h11_gender' => 'required',
            'h12' => 'required',
            'h12_gender' => 'required',
            'h13' => 'required',
            'h13_gender' => 'required',
            'h14' => 'required',
            'h14_gender' => 'required',
            'h15' => 'required',
            'h15_gender' => 'required',
            'h16' => 'required',
            'h16_gender' => 'required',
            'h17' => 'required',
            'h17_gender' => 'required',
            'h18' => 'required',
            'h18_gender' => 'required',
            'h19' => 'required',
            'h19_gender' => 'required',
            'h20' => 'required',
            'h20_gender' => 'required',
            'h21' => 'required',
            'h21_gender' => 'required',
            'h22' => 'required',
            'h22_gender' => 'required',
            'd1'=> 'required',
            'd2'=> 'required',
            'd3'=> 'required',
            'd4'=> 'required',
            'd5'=> 'required',
            'd6'=> 'required',
            'd7'=> 'required',
            'closed_days' => 'required',
            'open_days' => 'required',
        ];
//        $this->validate($request , $rules);
        $config = Config::find(1);
        $config->fill($request->only([
            'h8' ,
            'h8_gender'  ,
            'h9' ,
            'h9_gender' ,
            'h10',
            'h10_gender' ,
            'h11',
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
            'h22_gender',
            'd1',
            'd2',
            'd3',
            'd4',
            'd5',
            'd6',
            'd7',
            'closed_days' ,
            'open_days' ,
        ]));

        $config->save();
        
        return $this->showOne($config);
    }

    public function getDay($day){
        switch ($day){
            case 'شنبه':
                return 'd1';
            case 'یکشنبه':
                $result = 'd2';
                return 'd2';
            case 'دوشنبه':
                return 'd3';
            case 'سه شنبه':
                return 'd4';
            case 'چهارشنبه':
                return 'd5';
            case 'پنجشنبه':
                return 'd6';
            case 'جمعه':
                return 'd7';
        }
    }
}
