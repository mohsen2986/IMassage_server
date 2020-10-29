<?php

namespace App\Http\Controllers\Boarder;

use App\Boarder;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use PhpParser\JsonDecoder;

class BoarderController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        return $this->showAll(Boarder::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {

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
            'title' => 'required' ,
            'image' => 'required|image' ,
            'description' => 'required'
        ];
        $this->validate($request , $rules);
        // save data
        $data = request()->all();
        // store data
        $data['image'] = request('image')->store('');
        // store data
        $boarder = Boarder::create($data);

        return $this->showOne($boarder);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
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
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
