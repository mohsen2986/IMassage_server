<?php

namespace App\Http\Controllers\Question;

use App\filledQuestion;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class FilledQustionController extends ApiCOntroller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        return $this->showAll(filledQuestion::all());
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
            'filled_form_id' => 'required',
            'question_id' => 'required',
            'answer' => 'required'
        ];
        $this->validate($request , $rules);
        // TODO CHECK FILLED_FORM AND QUESTION ID EXIST
        // store data
        $data = request()->all();
        // store FilledQuestion to the database
        $filledQuestion = FilledQuestion::create($data);

        return $this->showOne($filledQuestion);

    }

    /**
     * Display the specified resource.
     *
     * @param filledQuestion $fillQuestion
     * @return JsonResponse
     */
    public function show(filledQuestion $fillQuestion)
    {
        return $this->showOne($fillQuestion);
    }

}
