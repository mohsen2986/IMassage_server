<?php

namespace App\Http\Controllers\Transactions;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Transactions;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
        'ref_id' => 'required',
        'amount' => 'required',
        ];
        $this->validate($request , $rules);
        // store data
        $data = request()->all();
        // set validation for transactions
        $data['valid_transaction'] = Transactions::VALID;
        $dta['is_used'] = Transactions::IS_NOT_USED;
        // get user from Auth
        $data['user_id'] = 1; // todo get user
        // store transactions to the database
        $transactions = Transactions::create($data);

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

}
