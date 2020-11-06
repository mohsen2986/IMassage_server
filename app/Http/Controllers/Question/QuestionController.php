<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Question;
use App\QuestionType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class QuestionController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        return $this->showAll(Question::all());
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
            'question' => 'required',
            'question_type_id'=> 'required'
        ];
        $this->validate($request, $rules);
        // check that question_type_id exist
        $question_type_id = QuestionType::find(request('question_type_id'));
        if($question_type_id){
            $this->errorResponse('question type dosent exist' , 422);
        }
        // store data
        $data = request()->all();
        // store data
        $question = Question::create(request()->all());

        return $this->showOne($question);
    }

    /**
     * Display the specified resource.
     *
     * @param Question $question
     * @return JsonResponse
     */
    public function show(Question $question)
    {
        return $this->showOne($question);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  Question $question
     * @return JsonResponse
     */
    public function update(Request $request, Question $question)
    {
        $question->fill($request->only(([
            'question'
        ])));
        if($question->isClean()){
            return $this->errorResponse('you need to specify to different value' , 422);
        }
        // save new data
        $question->save();

        return $this->showOne($question);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Question $question
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(Question $question)
    {
        // delete from database
        $question->delete();

        return $this->showOne($question);
    }
}
