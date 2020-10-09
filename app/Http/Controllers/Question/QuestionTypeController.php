<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\QuestionType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class QuestionTypeController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $questionTypes = QuestionType::all();
        return $this->showAll($questionTypes);
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
        $rules = [
            'type' => 'required',
        ];
        $this->validate($request , $rules);
        $newQuestionType = QuestionType::create($request->all());

        return $this->showOne($newQuestionType);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param QuestionType $questionType
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(QuestionType $questionType)
    {
        $questionType->delete();
        return $this->showOne($questionType);
    }
}
