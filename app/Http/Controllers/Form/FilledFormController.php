<?php

namespace App\Http\Controllers\Form;

use App\FilledForm;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class FilledFormController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        return $this->showAll(FilledForm::all());
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
            'form_id' => 'required',
        ];

        $this->validate($request , $rules);
        // store data
        $data = request()->all();
        // get date
        $temp = explode(' ', jdate());
        $data['date'] = $temp[0];
        // TODO GET USER ID FROM AUTH  user_id
        $data['user_id'] = 1;
        // TODO CHECK FORM EXIST
        // store data to the database
        $filledForm = FilledForm::create($data);

        return $this->showOne($filledForm);
    }

    /**
     * Display the specified resource.
     *
     * @param  FilledForm $filledForm
     * @return JsonResponse
     */
    public function show(FilledForm $filledForm)
    {
        return $this->showOne($filledForm);
    }
}
