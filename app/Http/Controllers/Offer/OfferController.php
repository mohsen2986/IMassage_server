<?php

namespace App\Http\Controllers\Offer;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Offers;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
require_once base_path('vendor/tcpdf-master/tcpdf.php');
require_once base_path('app/generatePdf.php');

class OfferController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $offers = Offers::all();
        return $this->showAll($offers , 200 , true);
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
            'number' => 'required',
            'percent' => 'required' ,
            'massage' => 'required',
            'start_date' => 'required',
            'expire_date' => 'required',
        ];
        $this->validate($request , $rules);
        // todo must check the massage-> id is valid
        // generate data
        // hold offers for response
        $offers = new Collection();
        for($i=0 ; $i<request('number'); $i++){
            // set data
            $data['code'] = Offers::generateCode();
            $temp = explode(' ', jdate());
            $data['date'] = $temp[0];
            $data['validate'] = Offers::VALIDATE;
            $data['offer'] = request('percent');
            $data['massage_id'] = request('massage');
            $data['start_date'] = request('start_date');
            $data['expire_date'] = request('expire_date');
            // store offer
            $offers->push(Offers::create($data));
            // add it to the response
        }
        return $this->showAll($offers);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Offers $offer
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(Offers $offer)
    {
        // delete the offer from the database
        $offer->delete();

        return $this->showOne($offer);
    }
    public function test(){
        $all = Offers::all()->where('validate' , '=' , Offers::VALIDATE);
        return generateOfferCode($all);
    }
}
