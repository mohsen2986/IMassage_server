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

class OfferController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        return $this->showAll(Offers::all());
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
            'percent' => 'required'
        ];
        $this->validate($request , $rules);
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
}
