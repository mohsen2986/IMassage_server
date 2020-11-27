<?php

namespace App\Http\Controllers\Form;

use App\Form;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class FormController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        return $this->showAll(Form::all());
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
            'name' => 'required',
        ];
        $this->validate($request , $rules);
        // save data
        $data = request()->all();
        // store data to database
        $form = Form::create($data);

        return $this->showOne($form);
    }

    /**
     * Display the specified resource.
     *
     * @param  Form $form
     * @return JsonResponse
     */
    public function show(Form $form)
    {
        return $this->showOne($form);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  Form $form
     * @return JsonResponse
     */
    public function update(Request $request, Form $form)
    {
        $form->fill($request->only([
            'name' ,
        ]));
        if($form->isClean()){
            return $this->errorResponse('you need to specify to different value' , 422);
        }
        // save new instance
        $form->save();

        return $this->showOne($form);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Form $form
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(Form $form)
    {
        // delete from database
        $form->delete();

        return $this->showOne($form);
    }
}
