<?php

namespace App\Traits;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;
use PhpParser\Node\Expr\Array_;


trait ApiResponser
{
    private function successResponse($data, $code)
    {
        return response()->json($data, $code);
    }

    protected function errorResponse($message, $code)
    {
        return response()->json(['error' => $message, 'code' => $code], $code);
    }

    protected function showAll(Collection $collection, $code = 200)
    {
        if ($collection->isEmpty()) {
            return $this->successResponse(['datas' => $collection], $code);
        }
        return $this->successResponse(['datas' => $collection], $code);
    }
    protected function showList(Array $array , $code = 200){
        // todo implement for empty array
//        if ($array->) {
//            return $this->successResponse(['datas' => $array], $code);
//        }
        return $this->successResponse(['datas' => $array], $code);
    }

    protected function showOne(Model $instance, $code = 200)
    {
        return $this->successResponse($instance, $code);
    }

}

