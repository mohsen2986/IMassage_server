<?php

namespace App\Http\Controllers\Order;

use App\FilledForm;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Massage;
use App\Order;
use App\Packages;
use App\ReservedTimeDates;
use App\SmsToken;
use App\Time;
use App\Transactions;
use App\User;
use Carbon\Carbon;
use Facade\Ignition\Support\Packagist\Package;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        $orders = Order::all();
        foreach ($orders as $order){
            $order->massage;
            $order->packages;
            $order->transactions;
            $order->reservedTimeDates->pluck('date');
            $order->times;
        }
        return $this->showAll($orders , 200, true);
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
//        $reservedTimeDates = ReservedTimeDates::find(request('reserved_time_date_id'));
        $reservedTimeDates = ReservedTimeDates::where('date' , '=' , request('reserved_time_date_id'))->first();
        $gender = request('gender');
        // check data
        if($massage && $package && $transaction && $reservedTimeDates ){
            // check gender
            // check time
            $startTime = ((int) substr($time , 1));
//            $endTime = ((int) substr($time , 1))+$massage->length;  // todo migrate to package
            $endTime = ((int) substr($time , 1))+$package->length;
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
                $transaction->valid_transaction = Transactions::INVALID;
                $transaction->save(); // save to database

                // set reserved time in ReservedTimeDates
                for($temp = $startTime ; $temp != $endTime ; $temp++){
                    $reservedTimeDates['h'.$temp] = ReservedTimeDates::RESERVED;
                }
                $reservedTimeDates->save();

                $data = request()->all();
                // get user form Auth layer
                $user = Auth::user();
                $data['user_id']  = $user->id;
                $data['reserved_time_date_id'] = $reservedTimeDates->id;
                // get Filled Form id
                $filledForm = FilledForm::where('user_id' , '=' , $user->id)->first();
                $data['filled_form_id'] = $filledForm->id;
                //  store data
                $order = Order::create($data);
                // create time table
                for($temp = $startTime ; $temp != $endTime ; $temp++){
                    $timeDate['time'] = 'H'.$temp;
                    $timeDate['order_id'] = $order->id;
                    Time::create($timeDate);
                }
                return $this->showOne($order);

            }else{
                return $this->errorResponse('the transaction is invalid' ,422);
            }

        }else{
            // incorrect data
            return $this->errorResponse("the data are invalid", 422);
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
            'package_id' => 'required',
            'gender' => 'required'
        ];
        $this->validate($request, $rules);
        // get data
        $time = request('time');
//        $massage = Massage::find(request('massage_id'));
        $package = Packages::find(request('package'));
//        $reservedTimeDates = ReservedTimeDates::find(request('reserved_time_date_id'));
        $reservedTimeDates = ReservedTimeDates::where('date' , '=' , request('reserved_time_date_id'))->first();
        $gender = request('gender');
        // check data
        if ( $package && $reservedTimeDates) {
            // check gender
            // check time
            $startTime = ((int)substr($time, 1));
//            $endTime = ((int)substr($time, 1)) + $massage->length; // todo migrate to package
            $endTime = ((int)substr($time, 1)) + $package->length;
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
        return $this->errorResponse('the time have ben reserved', 422);
        return response()->json(['status' => 'the time is valid', 'code' => 200], 200);
    }

    /**
     * @param Request $request
     */
    public function order(Request $request){
        $nowTime = Carbon::now()->hour;
//        $user = User::find(1);
        $user = Auth::User();
        $reservedTimes = new Collection();
//        echo $order[2]->times;
//        echo $user->orders->time;
        $temp = explode(' ', jdate());
        $today = ReservedTimeDates::where('date' , '=' , $temp[0])->first();
        if($dates = ReservedTimeDates::where('id' , '>=' , $today->id)->get()){
            foreach ($dates as $date){
                if($orders = Order::where('reserved_time_date_id' , '=' , $date->id)
                    ->where('user_id' , '=' , $user->id)->get()){
                    foreach ($orders as $order){
                        // if order is today
                        if($order->reserved_time_date_id == $today->id) {
                            foreach ($order->times as $time) {
                                if (substr($time['time'], 1) >= $nowTime) {
                                    $reservedTimes->push($order);
                                break;
                                }
                            }
                        }
                        else{
                            $order->massage;
                            $reservedTimes->push($order);
                        }
                    }
                }
            }
            $returnData = new Collection();
            foreach ($reservedTimes as $order){
                $order->massage;
                $order->packages;
                $order->transactions;
                $order->reservedTimeDates->pluck('date');
                $order->times;
                $returnData->push($order);
            }
            return $this->showAll($returnData);
//            return $this->showAll($orders , 200, true);
        }
    }

    /**
     *
     */
    public function allOrderHistory(){
        // get UserId from Auth
        $user = Auth::User();
//        $user = 1;
//        $user = 1;
        $orders = Order::where('user_id' , '=' , $user->id)->get();
        $returnData = new Collection();
//        foreach ($orders as $order){
//            $order->massage;
//            $order->packages;
//            $order->transactions;
//            $order->reservedTimeDates->pluck('date');
//            $order->times;
//            $returnData->push($order);
//        }
//        return $this->showAll($returnData);
        return $this->showAll($orders , 200, true);
    }
    public function allOrderHistory_(){
        // get UserId from Auth
        $user = Auth::User();
//        $user = 1;
//        $user = 1;
        $orders = Order::where('user_id' , '=' , $user->id)->get();
        $returnData = new Collection();
        foreach ($orders as $order){
            $order->massage;
            $order->packages;
            $order->transactions;
            $order->reservedTimeDates->pluck('date');
            $order->times;
            $returnData->push($order);
        }
        return $this->showAll($returnData , 200, true);
    }

    public function reservedOrders(Request $request){
        $nowTime = Carbon::now()->hour;
        $reservedTimes = new Collection();

        $temp = explode(' ', jdate());
        $today = ReservedTimeDates::where('date' , '=' , $temp[0])->first();
        if($dates = ReservedTimeDates::where('id' , '>=' , $today->id)->get()) {
            foreach ($dates as $date) {
                if ($orders = Order::where('reserved_time_date_id', '=', $date->id)->get()) {
                    foreach ($orders as $order) {
                        // if order is today
                        if ($order->reserved_time_date_id == $today->id) {
                            foreach ($order->times as $time) {
                                if (substr($time['time'], 1) >= $nowTime) {
                                    $reservedTimes->push($order);
                                    break;
                                }
                            }
                        } else {
                            // else for tomorrow or else....
                            $order->massage;
                            $reservedTimes->push($order);
                        }
                    }
                }
            }
//            reservedOrders
            return $this->showAll($reservedTimes , 200 , true);
        }
    }

    public function getConsultingUsers(){
        $users = DB::table('consulting')
            ->join('users' , 'consulting.user_id' , '=' , 'users.id')
            ->where('is_consulting' , '=' , User::NEED_CONSULTING)
            ->get();

        return $this->showAll($users);
    }

    public function setConsultingUser(Request $request){
        $rules =[
            'user' => 'required'
        ];
        $this->validate($request , $rules);

         DB::table('consulting')
             ->where('user_id' , '=' , request('user'))
             ->update(['is_consulting' => User::DO_NOT_NEED_CONSULTING]);


    }
}
