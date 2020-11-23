<?php

namespace App\Traits;
use App\Transformers\UserTransformer;
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

    protected function showAll(Collection $collection, $code = 200 , $paginate = false)
    {
        if ($collection->isEmpty()) {
            return $this->successResponse(['datas' => $collection], $code);
        }

            if ($paginate) {
                $transformer = $collection->first()->transformer;
                $collection = $this->paginate($collection);
                $collection = $this->transformData($collection, $transformer);
                return $this->successResponse($collection, $code);
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

    // paginate
    protected function paginate(Collection $collection){
        $rules = [
            'per_page' => 'integer|min:2|max:50',
        ];

        Validator::validate(request()->all(), $rules);

        $page = LengthAwarePaginator::resolveCurrentPage();

        $perPage = 15;
        if (request()->has('per_page')) {
            $perPage = (int) request()->per_page;
        }

        $results = $collection->slice(($page - 1) * $perPage, $perPage)->values();

        $paginated = new LengthAwarePaginator($results, $collection->count(), $perPage, $page, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ]);

        $paginated->appends(request()->all());

        return $paginated;

    }
    private function transformData($data , $transformer)
    {
        $transformation = fractal($data, new $transformer);

        return $transformation->toArray();
    }

}

