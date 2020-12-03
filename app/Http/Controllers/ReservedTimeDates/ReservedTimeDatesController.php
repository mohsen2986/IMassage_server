<?php

namespace App\Http\Controllers\ReservedTimeDates;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Massage;
use App\Order;
use App\Packages;
use App\ReservedTimeDates;
use App\Time;
use App\Transactions;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Morilog\Jalali\CalendarUtils;
use Morilog\Jalali\Jalalian;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Null_;
use Psy\Util\Json;

class ReservedTimeDatesController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        return $this->showAll(ReservedTImeDates::all());
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
            'start_male_time' => 'required',
            'end_male_time' => 'required',
            'start_female_time' => 'required',
            'end_female_time' => 'required'
        ];
//        $this->validate($request , $rules);
        // get the times
        $startMaleTime = request('start_male_time');
        $endMaleTime = request('end_male_time');
        $startFemaleTime = request('start_female_time');
        $endFemaleTime = request('end_female_time');

        // create new instance
        // get time
        $temp = explode(' ', jdate());
        $datas['date'] = $temp[0];
        // specify the genders time

        // store new instance to the database
        $reservedTimeDates  = ReservedTimeDates::create($datas);

        return $this->showOne($reservedTimeDates);
    }

    /**
     * Display the specified resource.
     *
     * @param ReservedTimeDates $reservedTImeDate
     * @return JsonResponse
     */
    public function show(ReservedTimeDates $reservedTImeDate)
    {
        return $this->showOne($reservedTImeDate);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ReservedTimeDates $reservedTimeDate
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(ReservedTimeDates $reservedTimeDate)
    {
        $reservedTimeDate->delete();

        return $this->showOne($reservedTimeDate);
    }

    // return the available times
    public function getTimes(){
        $temp = explode(' ', jdate());
        $date = $temp[0];
        $today = ReservedTimeDates::where('date' , '=' , $date)->first();
        $dates = ReservedTimeDates::where('id' , '>=' , $today->id)->get();


        return $this->showAll($dates->pluck('date'));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function getAvailableTimeDate(Request $request){
        $rules = [
            'date' => 'required'
        ];
        $this->validate($request , $rules);
        $reservedTimeDates = ReservedTimeDates::where('date' , '=' , request('date'))->first();
        $times = DB::table('temp_times')->where('date', '=', $reservedTimeDates->date)->get();
        if(!$times){
            return $this->errorResponse('the time is incorrect' , 422);
        }
        $tempTimes = new ReservedTimeDates([
            'date' => $reservedTimeDates->date ,
            'h8' => $reservedTimeDates->h8,
            'h8_gender' => $reservedTimeDates->h8_gender,
            'h9' => $reservedTimeDates->h9,
            'h9_gender' => $reservedTimeDates->h9_gender,
            'h10' => $reservedTimeDates->h10,
            'h10_gender' => $reservedTimeDates->h10_gender,
            'h11' => $reservedTimeDates->h11,
            'h11_gender' => $reservedTimeDates->h11_gender,
            'h12' => $reservedTimeDates->h12,
            'h12_gender' => $reservedTimeDates->h12_gender,
            'h13' => $reservedTimeDates->h13,
            'h13_gender' => $reservedTimeDates->h13_gender,
            'h14' => $reservedTimeDates->h14,
            'h14_gender' => $reservedTimeDates->h14_gender,
            'h15' => $reservedTimeDates->h15,
            'h15_gender' => $reservedTimeDates->h15_gender,
            'h16' => $reservedTimeDates->h16,
            'h16_gender' => $reservedTimeDates->h16_gender,
            'h17' => $reservedTimeDates->h17,
            'h17_gender' => $reservedTimeDates->h17_gender,
            'h18' => $reservedTimeDates->h18,
            'h18_gender' => $reservedTimeDates->h18_gender,
            'h19' => $reservedTimeDates->h19,
            'h19_gender' => $reservedTimeDates->h19_gender,
            'h20' => $reservedTimeDates->h20,
            'h20_gender' => $reservedTimeDates->h20_gender,
            'h21' => $reservedTimeDates->h21,
            'h21_gender' => $reservedTimeDates->h21_gender,
            'h22' => $reservedTimeDates->h22,
            'h22_gender' => $reservedTimeDates->h22_gender,
        ]);
        if($times) {
            foreach ($times as $time) {
                if ($this->checkTime($time) < 20) {
                    $this->insertNewTimes($time, $tempTimes);
                }
            }
        }

        return $this->showOne($tempTimes);
    }

    public function reserve(Request $request){
        $rules = [
            'time' => 'required' ,
            'reserved_time_date_id' => 'required',
            'massage_id' => 'required',
            'package_id' => 'required',
            'gender' => 'required'
        ];
        $this->validate($request , $rules);
        // get data
        $time = request('time');
        $massage = Massage::find(request('massage_id'));
        $package = Packages::find(request('package_id'));
//        $reservedTimeDates = ReservedTimeDates::find(request('reserved_time_date_id'));
        $reservedTimeDates = ReservedTimeDates::where('date' , '=' , request('reserved_time_date_id'))->first();
        $gender = request('gender');
        // check data
        if($massage && $package && $reservedTimeDates) {
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

            // time not reserved
            // check transaction is valid

            $this->insetNewReserve($reservedTimeDates , $reservedTimeDates , $massage , $startTime);
            DB::table('temp_times')->insert([
                    'date' => $reservedTimeDates->date ,
                    'h8' => $reservedTimeDates->h8,
                    'h8_gender' => $reservedTimeDates->h8_gender,
                    'h9' => $reservedTimeDates->h9,
                    'h9_gender' => $reservedTimeDates->h9_gender,
                    'h10' => $reservedTimeDates->h10,
                    'h10_gender' => $reservedTimeDates->h10_gender,
                    'h11' => $reservedTimeDates->h11,
                    'h11_gender' => $reservedTimeDates->h11_gender,
                    'h12' => $reservedTimeDates->h12,
                    'h12_gender' => $reservedTimeDates->h12_gender,
                    'h13' => $reservedTimeDates->h13,
                    'h13_gender' => $reservedTimeDates->h13_gender,
                    'h14' => $reservedTimeDates->h14,
                    'h14_gender' => $reservedTimeDates->h14_gender,
                    'h15' => $reservedTimeDates->h15,
                    'h15_gender' => $reservedTimeDates->h15_gender,
                    'h16' => $reservedTimeDates->h16,
                    'h16_gender' => $reservedTimeDates->h16_gender,
                    'h17' => $reservedTimeDates->h17,
                    'h17_gender' => $reservedTimeDates->h17_gender,
                    'h18' => $reservedTimeDates->h18,
                    'h18_gender' => $reservedTimeDates->h18_gender,
                    'h19' => $reservedTimeDates->h19,
                    'h19_gender' => $reservedTimeDates->h19_gender,
                    'h20' => $reservedTimeDates->h20,
                    'h20_gender' => $reservedTimeDates->h20_gender,
                    'h21' => $reservedTimeDates->h21,
                    'h21_gender' => $reservedTimeDates->h21_gender,
                    'h22' => $reservedTimeDates->h22,
                    'h22_gender' => $reservedTimeDates->h22_gender,
                    'updated_at' => Carbon::now() ,
                    'created_at' => Carbon::now() ,
                ]);
                return response()->json('reserve the time', 200);
//                return $this->successResponse('reserve the time' , 201);
            /*
            $times = DB::table('temp_times')->where('date', '=', $reservedTimeDates->date)->get();
            $tt = new ReservedTimeDates([
                'date' => $reservedTimeDates->date ,
                'h8' => $reservedTimeDates->h8,
                'h8_gender' => $reservedTimeDates->h8_gender,
                'h9' => $reservedTimeDates->h9,
                'h9_gender' => $reservedTimeDates->h9_gender,
                'h10' => $reservedTimeDates->h10,
                'h10_gender' => $reservedTimeDates->h10_gender,
                'h11' => $reservedTimeDates->h11,
                'h11_gender' => $reservedTimeDates->h11_gender,
                'h12' => $reservedTimeDates->h12,
                'h12_gender' => $reservedTimeDates->h12_gender,
                'h13' => $reservedTimeDates->h13,
                'h13_gender' => $reservedTimeDates->h13_gender,
                'h14' => $reservedTimeDates->h14,
                'h14_gender' => $reservedTimeDates->h14_gender,
                'h15' => $reservedTimeDates->h15,
                'h15_gender' => $reservedTimeDates->h15_gender,
                'h16' => $reservedTimeDates->h16,
                'h16_gender' => $reservedTimeDates->h16_gender,
                'h17' => $reservedTimeDates->h17,
                'h17_gender' => $reservedTimeDates->h17_gender,
                'h18' => $reservedTimeDates->h18,
                'h18_gender' => $reservedTimeDates->h18_gender,
                'h19' => $reservedTimeDates->h19,
                'h19_gender' => $reservedTimeDates->h19_gender,
                'h20' => $reservedTimeDates->h20,
                'h20_gender' => $reservedTimeDates->h20_gender,
                'h21' => $reservedTimeDates->h21,
                'h21_gender' => $reservedTimeDates->h21_gender,
                'h22' => $reservedTimeDates->h22,
                'h22_gender' => $reservedTimeDates->h22_gender,
            ]);
//            $this->insertNewTimes($times[1] , $tt);
//            $this->insetNewReserve($tt , $tt , $massage , 11);
//            echo $time->diffInMinutes($now);
            foreach ($times as $time){
                if($this->checkTime($time) < 40){
                    $this->insertNewTimes($time , $tt);
                }

            }*/
            }else{
            return $this->errorResponse('invalid data' , 422);
        }
        }
        private function checkTime($time){
            $now = Carbon::now();
            $reserveTime = Carbon::parse($time->created_at);
            return $reserveTime->diffInMinutes($now);
        }
        private function insertNewTimes($time , $element){
            for($i= 8 ; $i<23 ; $i++){
                $hours = 'h'.$i;
                if($time->$hours == '1'){
                    $element->$hours = '1';
                }
            }
            return $element;
        }
        private function insetNewReserve($time , $element , $massage , $hour){
            $element['date'] = $time['date'];
            $massageTime = $massage['length'];
            $hour_ = (int) $hour;
            for($i= 8 ; $i<23 ; $i++){
                $hours = 'h'.$i;
                $element->$hours = $time->$hours;
            }
            for($i= 0 ; $i<$massageTime ; $i++){
                $hours = 'h'.($i+$hour_);
                $element->$hours = '1';
            }
            return $element;
        }

}
