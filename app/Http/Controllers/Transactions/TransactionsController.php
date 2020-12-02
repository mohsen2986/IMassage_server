<?php

namespace App\Http\Controllers\Transactions;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Offers;
use App\Packages;
use App\Transactions;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class TransactionsController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        return $this->showAll(Transactions::all());
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
        // todo note that get user id from Auth layer
        //             'user_id'

        $rules = [
        'package_id'=> 'required' ,
        'offer_code' ,
        ];
        $this->validate($request , $rules);
        $offer = Offers::where('code' , '=' , request('offer_code'))->first();
        if ($offer){
            if($offer->validate == Offers::VALIDATE){
                // invalid the offer code
                $offer->validate = Offers::INVALIDATE;
                $offer->save();
            }else{
                // the offer code is used
                return $this->errorResponse('the offer code is invalid' , 422);
            }
        }
        $package = Packages::find(request('package_id'));
        if(!$package){
            return $this->errorResponse('the package is incorrect' , 422);
        }
        if(!$offer && $request->offer_code){
            return $this->errorResponse('thr offer code is invalid!' , 422);
        }
        // store data
        $data = request()->all();
        // set validation for transactions
        $data['valid_transaction'] = Transactions::VALID;
        $data['is_used'] = Transactions::IS_NOT_USED;
        $data['ref_id'] = Transactions::NOT_PAYED;
        // calculate cost
        $data['amount'] = $package->cost;
        if($offer)
            $data['amount_with_offer'] = ($data['amount'] - ((((int)$data['amount'])/100)*$offer->offer));
        else
            $data['amount_with_offer'] = $data['amount'];
        // get user from Auth
        $user = Auth::user();
        $data['user_id'] = $user->id; // todo get user
        // store transactions to the database
        $transactions = Transactions::create($data);
        if($offer) {
            // if had offer code attach with the transaction
            $transactions->offer()->sync([$offer->id]);
        }

        return $this->showOne($transactions);
    }

    /**
     * Display the specified resource.
     *
     * @param  Transactions $transaction
     * @return JsonResponse
     */
    public function show(Transactions $transaction)
    {
        return $this->showOne($transaction);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function offer(Request $request){
        $rules = [
            'transaction_id'=> 'required' ,
            'offer_code' => 'required',
        ];
        $this->validate($request , $rules);

        $offer = Offers::where('code' , '=' , request('offer_code'))->first();
        if ($offer){
            if($offer->validate == Offers::VALIDATE){
                // invalid the offer code
                $offer->validate = Offers::INVALIDATE;
                $offer->save();
            }else{
                // the offer code is used
                return $this->errorResponse('the offer code is invalid' , 422);
            }
        }
        if(!$offer && $request->offer_code){
            return $this->errorResponse('thr offer code is invalid!' , 422);
        }
        // store data
        $transaction = Transactions::find(request('transaction_id'));
        if($transaction){
            $data['amount'] = $transaction->amount;
            if($offer)
                $transaction->amount_with_offer = ($data['amount'] - ((((int)$data['amount'])/100)*$offer->offer));
            else
                $transaction->amount_with_offer = $data['amount'];

            // save the offer
            $transaction->save();
            if($offer) {
                // if had offer code attach with the transaction
                $transaction->offer()->sync([$offer->id]);
            }
            return $this->showOne($transaction);
        }
        return $this->errorResponse('the transaction is invalid' , 422);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function payTransaction(Request $request){
        $rules = [
            'transaction_id' => 'required' ,
            'ref_id' => 'required',
        ];
        $this->validate($request , $rules);
        // find the valid transaction
        $transaction = Transactions::find(request('transaction_id'));
        if($transaction){
            // validate the transactions
            $transaction->valid_transaction = Transactions::VALID;
            return $this->showOne($transaction);
        }else{
            // the transaction is invalid
            return $this->errorResponse('transaction is invalid!' , 422);
        }
    }

}
