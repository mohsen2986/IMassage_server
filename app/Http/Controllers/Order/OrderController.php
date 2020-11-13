<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Massage;
use App\Order;
use App\Packages;
use App\ReservedTimeDates;
use App\Transactions;
use Facade\Ignition\Support\Packagist\Package;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class OrderController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        return $this->showAll(Order::all());
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
            'filled_form_id' => 'required' ,
            'time' => 'required' ,
            'reserved_time_date_id' => 'required',
            'massage_id' => 'required',
            'package_id' => 'required',
            'transactions_id' => 'required' ,
            'gender' => 'required'
        ];
        $this->validate($request , $rules);
        // get data
        $time = request('time');
        $massage = Massage::find(request('massage_id'));
        $package = Packages::find(request('package_id'));
        $transaction = Transactions::find(request('transactions_id'));
        $reservedTimeDates = ReservedTimeDates::find(request('reserved_time_date_id'));
        $gender = request('gender');
        // check data
        if($massage && $package && $transaction && $reservedTimeDates ){
            // check gender
            // check time
            $startTime = ((int) substr($time , 1));
            $endTime = ((int) substr($time , 1))+$massage->length;
            $temp = $startTime;
            while($temp != ($endTime)){
                if($reservedTimeDates['h'.$temp] ==  ReservedTimeDates::RESERVED){
                    return $this->errorResponse('the time have ben reserved' , 422);
                }
                if($reservedTimeDates['h'.$temp.'_gender'] !=  $gender){
                    return $this->errorResponse('the gender is wrong', 422);
                }
                $temp++;
            }

            // time not reserved
            // check transaction is valid
            // todo check valid request to payment gate for validation
            if($transaction->valid_transaction == Transactions::VALID){
                $transaction->valid_transaction = Transactions::INVALID; // TODO CHECK THIS INVALID!!!! :)
                $transaction->save(); // save to database

                // set reserved time in ReservedTimeDates
                for($temp = $startTime ; $temp != $endTime ; $temp++){
                    $reservedTimeDates['h'.$temp] = ReservedTimeDates::RESERVED;
                }
                $reservedTimeDates->save();

                $data = request()->all();
                // get user form Auth layer
                $user = Auth::user();
                $data['user_id']  =$user->id; // todo GET USER FROM AUTH
                //  store data
                $order = Order::create($data);

                return $this->showOne($order);

            }

        }else{
            // incorrect data
            dd('not found');

        }

    }

    /**
     * Display the specified resource.
     *
     * @param  Order $order
     * @return JsonResponse
     */
    public function show(Order $order)
    {
        return $this->showOne($order);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //  TODO IF WANT TO DELETE A ORDER

    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function checkTime(Request $request)
    {
        $rules = [
            'time' => 'required',
            'reserved_time_date_id' => 'required',
            'massage_id' => 'required',
            'gender' => 'required'
        ];
        $this->validate($request, $rules);
        // get data
        $time = request('time');
        $massage = Massage::find(request('massage_id'));
        $reservedTimeDates = ReservedTimeDates::find(request('reserved_time_date_id'));
        $gender = request('gender');
        // check data
        if ($massage && $reservedTimeDates) {
            // check gender
            // check time
            $startTime = ((int)substr($time, 1));
            $endTime = ((int)substr($time, 1)) + $massage->length;
            $temp = $startTime;
            while ($temp != ($endTime)) {
                if ($reservedTimeDates['h' . $temp] == ReservedTimeDates::RESERVED) {
                    return $this->errorResponse('the time have ben reserved', 422);
                }
                if ($reservedTimeDates['h' . $temp . '_gender'] != $gender) {
                    return $this->errorResponse('the gender is wrong', 422);
                }
                $temp++;
            }

        }
        return response()->json(['status' => 'the time is valid', 'code' => 200], 200);
    }
}
