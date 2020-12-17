<?php

namespace App\Http\Controllers\Question;

use App\Config;
use App\FilledForm;
use App\filledQuestion;
use App\Http\Controllers\ApiController;
use App\ReservedTimeDates;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Morilog\Jalali\CalendarUtils;
use Morilog\Jalali\Jalalian;

class QuestionConfig extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $config = Config::all()->last();
        $questions = $config->form->question;
        return $this->showAll($questions);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        if(request('answers')){

            $config = Config::all()->last();
            $questions = $config->form->question;

            // check for validate request
            $valid = true;
            $counter =0;
            foreach($request->answers as $key=>$item){
                if($questions[$counter]->id != $item['question']){
                    $valid = false;
                    return $this->errorResponse('incorrect question ' , 422);
                }
                $counter++;
            }
            // save the response
            if($valid){
                $filledForm = null;
                // create new filled form
                $temp = explode(' ', jdate());
                $data['date'] = $temp[0];
                // get user from auth
                $user = Auth::user();
                $data['user_id'] = $user->id; // todo get from Auth layer
                $data['form_id'] = $config->form_id;
                $filledForm = FilledForm::create($data);

                foreach($request->answers as $key=>$item){
                    // create new filled form
//                    $temp = explode(' ', jdate());
//                    $data['date'] = $temp[0];
                    // get user from auth
//                    $user = Auth::user();
//                    $data['user_id'] = $user->id; // todo get from Auth layer
//                    $data['form_id'] = $config->id;
//                    $filledForm = FilledForm::create($data);
                    // save questions
                    $questionData['filled_form_id'] = $filledForm->id;
                    $questionData['question_id'] = $item['question'];
                    $questionData['answer'] = $item['answer'];
                    $filledQuestion = FilledQuestion::create($questionData);


//                    return response()->json('answers saved' , 200);
//                    return $this->showOne($filledQuestion);
                }
                return $this->showOne($filledForm);
            }

        }else {
            return $this->errorResponse('input format is invalid', 422);
        }
    }

    public function test(){

//        var_dump(Jalalian::forge('today')->format('%A'));
        $config = Config::find(1);
//        $reservedTimeDate = ReservedTimeDates::where('date' , '=', $data['date'])->first();
        for($day=0 ; $day < $config->open_days ; $day++){
            $temp = explode(' ', jdate()->addDays($day));
            $data['date'] = $temp[0]; // date

            $today = Jalalian::forge('today')->addDays($day)->format('%A');
            $todayDB = $this->getDay($today);

            $reservedTimeDate = ReservedTimeDates::where('date' , '=', $data['date'])->first();
            if(!$reservedTimeDate){
                if($config->closed_days == 0) {
                    if($config->$todayDB == Config::OPEN){
                        for($i = 8 ; $i<23 ; $i++){
                            $t = 'h'.$i;
                            $g = 'h'.$i.'_gender';
                            $data[$t] = $config->$t;
                            $data[$g] = $config->$g;
                        }
                        $time = ReservedTimeDates::create($data);
                    }
                }else{
                    $config->closed_days = $config->closed_days-1;
                    $config->save();
                }

            }

        }

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
