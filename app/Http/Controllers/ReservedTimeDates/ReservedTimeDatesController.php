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
use App\User;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Exception;
use Facade\Ignition\Support\Packagist\Package;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
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
        // validate request
        $this->validate($request , $rules);
        // get all reserved table
        $reservedTimeDates = ReservedTimeDates::where('date' , '=' , request('date'))->get();
        // get all temporary reserves
        $times = DB::table('temp_times')->where('date', '=', request('date'))->get();
        // get user gender
        $user = Auth::user();
        $gender = $user->gender;
        // holding available times for specific gender
        $availableTimes = array();
        // insert valid info to array
        for($i = 0 ; $i<25 ; $i++){
            $availableTimes[User::MALE_GENDER][] = 0;
            $availableTimes[User::FEMALE_GENDER][] = 0;
        }
        // get available times from reservedTimeDates
        $availableTimes = $this->generateAvailableTimes($availableTimes , $reservedTimeDates);
        // check temporary reserved
        if($times) {
            foreach ($times as $time) {
                if ($this->checkTime($time) < 20) {
                    $availableTimes = $this->insertNewTimes($time, $availableTimes);
                }
            }
        }
        // convert data to validate response
        $tempTimes = $this->mergeTimes($availableTimes , $gender);

        return $this->showOne($tempTimes);
    }

    public function reserve(Request $request)
    {
        $rules = [
            'time' => 'required',
            'reserved_time_date_id' => 'required',
            'package_id' => 'required',
        ];
        // validate input request
        $this->validate($request, $rules);
        // get time
        $time = request('time');
        // get package from DB
        $package = Packages::find(request('package_id'));
        // get all the reservation times
        $reservedTimeDates = ReservedTimeDates::where('date' , '=' , request('reserved_time_date_id'))->get();
        // get gender from auth
        $user = Auth::user();
        $gender = $user->gender;
        // check data
        if($package && $reservedTimeDates) {
            // get the start time
            $startTime = ((int)substr($time, 1));
            // get the end time
            $endTime = ((int)substr($time, 1)) + $package->length;
            // for holding start time calculation
            $temp = $startTime;
            // get the temp times
            $times = DB::table('temp_times')->where('date', '=', request('reserved_time_date_id'))->get();

            // check the package have time config and implement
            if(count($package->timeConfigs)){
                if(!$this->changePackageRangeTime($package , $startTime)){
                    return $this->errorResponse('the package is not available at this time' , 422);
                }
            }

            // holding available time based in gender
            $availableTimes = array();
            // fill the available time with valid data
            for($i = 0 ; $i<25 ; $i++){
                $availableTimes[User::MALE_GENDER][] = 0;
                $availableTimes[User::FEMALE_GENDER][] = 0;
            }
            // get available times
            $availableTimes = $this->generateAvailableTimes($availableTimes , $reservedTimeDates);
            // check temp reserves and add it to $availableTimes
            if($times) {
                foreach ($times as $time) {
                    if ($this->checkTime($time) < 20) {
                        $availableTimes = $this->insertNewTimes($time, $availableTimes);
                    }
                }
            }
            // check the request time
            while($temp != ($endTime)){
                if($gender == User::MALE_GENDER){
                    $availableTimes[User::MALE_GENDER][$temp] -= 1;
                }
                elseif($gender == User::FEMALE_GENDER){
                    $availableTimes[User::FEMALE_GENDER][$temp] -= 1;
                }
                $temp++;
            }

            for($i = 1 ; $i < 25 ; $i++){
                if($gender == User::MALE_GENDER){
                    if($availableTimes[User::MALE_GENDER][$i] < 0 )
                        return $this->errorResponse('the time have been reserved' , 422);
                }
                else if($gender == User::FEMALE_GENDER){
                    if($availableTimes[User::FEMALE_GENDER][$i] < 0){
                        return $this->errorResponse('the time have been reserved' , 422);
                    }
                }
            }
            // reserve data
            $reservedTimeDates = $this->insetNewReserve($reservedTimeDates , $package , $startTime , request('gender'));

            DB::table('temp_times')->insert([
                    'date' => $reservedTimeDates->date ,
                    'h1' => $reservedTimeDates->h1,
                    'h1_gender' => $reservedTimeDates->h1_gender,
                    'h2' => $reservedTimeDates->h2,
                    'h2_gender' => $reservedTimeDates->h2_gender,
                    'h3' => $reservedTimeDates->h3,
                    'h3_gender' => $reservedTimeDates->h3_gender,
                    'h4' => $reservedTimeDates->h4,
                    'h4_gender' => $reservedTimeDates->h4_gender,
                    'h5' => $reservedTimeDates->h5,
                    'h5_gender' => $reservedTimeDates->h5_gender,
                    'h6' => $reservedTimeDates->h6,
                    'h6_gender' => $reservedTimeDates->h6_gender,
                    'h7' => $reservedTimeDates->h7,
                    'h7_gender' => $reservedTimeDates->h7_gender,
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
                    'h23' => $reservedTimeDates->h23,
                    'h23_gender' => $reservedTimeDates->h23_gender,
                    'h24' => $reservedTimeDates->h24,
                    'h24_gender' => $reservedTimeDates->h24_gender,
                    'updated_at' => Carbon::now() ,
                    'created_at' => Carbon::now() ,
                ]);
                return response()->json('reserve the time', 200);

            }else{
            return $this->errorResponse('invalid data' , 422);
        }
        }
    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function checkTimes(Request $request)
    {
        $rules = [
            'time' => 'required',
            'reserved_time_date_id' => 'required',
            'package_id' => 'required',
        ];
        // validate input request
        $this->validate($request, $rules);
        // get time
        $time = request('time');
        // get package from DB
        $package = Packages::find(request('package_id'));
        // get all the reservation times
        $reservedTimeDates = ReservedTimeDates::where('date' , '=' , request('reserved_time_date_id'))->get();
        // get gender from auth
        $user = Auth::user();
        $gender = $user->gender;
        // check data
        if ( $package && $reservedTimeDates) {
            // get the start time
            $startTime = ((int)substr($time, 1));
            // get the end time
            $endTime = ((int)substr($time, 1)) + $package->length;
            // for holding start time calculation
            $temp = $startTime;
            // get the temp times
            $times = DB::table('temp_times')->where('date', '=', request('reserved_time_date_id'))->get();

            // check the package have time config and implement
            if(count($package->timeConfigs)){
                if(!$this->changePackageRangeTime($package , $startTime)){
                    return $this->errorResponse('the package is not available at this time' , 422);
                }
            }

            // holding available time based in gender
            $availableTimes = array();
            // fill the available time with valid data
            for($i = 0 ; $i<25 ; $i++){
                $availableTimes[User::MALE_GENDER][] = 0;
                $availableTimes[User::FEMALE_GENDER][] = 0;
            }
            // get available times
            $availableTimes = $this->generateAvailableTimes($availableTimes , $reservedTimeDates);
            // check temp reserves and add it to $availableTimes
            if($times) {
                foreach ($times as $time) {
                    if ($this->checkTime($time) < 20) {
                        $availableTimes = $this->insertNewTimes($time, $availableTimes);
                    }
                }
            }
            // check the request time
            while($temp != ($endTime)){
                if($gender == User::MALE_GENDER){
                    $availableTimes[User::MALE_GENDER][$temp] -= 1;
                }
                elseif($gender == User::FEMALE_GENDER){
                    $availableTimes[User::FEMALE_GENDER][$temp] -= 1;
                }
                $temp++;
            }

            for($i = 1 ; $i < 25 ; $i++){
                if($gender == User::MALE_GENDER){
                    if($availableTimes[User::MALE_GENDER][$i] < 0 )
                        return $this->errorResponse('the time have been reserved' , 422);
                }
                else if($gender == User::FEMALE_GENDER){
                    if($availableTimes[User::FEMALE_GENDER][$i] < 0){
                        return $this->errorResponse('the time have been reserved' , 422);
                    }
                }
            }
            return response()->json(['status' => 'the time is valid', 'code' => 200], 200);
        }else{
            return $this->errorResponse('wrong data', 422);
        }
    }

        private function checkTime($time){
            $now = Carbon::now();
            $reserveTime = Carbon::parse($time->created_at);
            return $reserveTime->diffInMinutes($now);
        }
        private function insertNewTimes($time , $element){
            for($i= 1 ; $i<25 ; $i++){
                $hours = 'h'.$i;
                $genderHour = 'h'.$i.'_gender';
                if($time->$genderHour == User::MALE_GENDER){
                    if($time->$hours == '1')
                        $element[User::MALE_GENDER][$i] -= 1;
                }elseif($time->$genderHour == User::FEMALE_GENDER){
                    if($time->$hours == '1')
                        $element[User::FEMALE_GENDER][$i] -= 1;
                }
            }
            return $element;
        }
        private function insetNewReserve($element , $package , $hour , $gender){
            $massageTime = $package['length'];
            $hour_ = (int) $hour;
            for($i= 1 ; $i<25 ; $i++){
                $hours = 'h'.$i;
                $genderHour = 'h'.$i.'_gender';
                $element->$hours = ReservedTimeDates::FREE;
                $element->$genderHour = $gender;
            }

            for($i= 0 ; $i<$massageTime ; $i++){
                $hours = 'h'.($i+$hour_);
                $element->$hours = '1';
            }
            return $element;
        }
        private function mergeTimes($firstElement , $gender){
            $temp = new ReservedTimeDates();
            for($i = 1 ; $i<25 ; $i++){
                $hour = 'h'.$i;
                $genderTime = 'h'.$i.'_gender';
                if($firstElement[$gender][$i] > 0)
                    $temp->$hour = ReservedTimeDates::FREE;
                else
                    $temp->$hour = ReservedTimeDates::RESERVED;

                $temp->$genderTime = $gender;
            }
            return $temp;
        }
        public function generateAvailableTimes($element , $reserves){
            foreach ($reserves as $reserve){
                for($i= 1 ;$i <25; $i++){
                    $hour = 'h'.$i;
                    $genderTime = 'h'.$i.'_gender';
                    if ($reserve->$genderTime == User::MALE_GENDER){
                        if($reserve->$hour == ReservedTimeDates::FREE)
                            $element[User::MALE_GENDER][$i] += 1;
                    }
                    elseif($reserve->$genderTime == User::FEMALE_GENDER){
                        if($reserve->$hour == ReservedTimeDates::FREE)
                            $element[User::FEMALE_GENDER][$i] += 1;
                    }
                }
            }
            return $element;
        }
        private function changePackageRangeTime($package , $requestTime){
            $packageRangeTimes = $package->timeConfigs->first();
            $startTime = $requestTime;
            $endTime = ($package->length + $startTime);

            while($startTime != $endTime){
                echo $packageRangeTimes['h'.$startTime];
                if($packageRangeTimes['h'.$startTime] == '1'){
                    return false;
                }
                $startTime++;
            }
            return true;
        }

}
