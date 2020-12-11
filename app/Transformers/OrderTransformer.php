<?php

namespace App\Transformers;

use App\Order;
use League\Fractal\TransformerAbstract;

class OrderTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        //
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        //
    ];

    /**
     * A Fractal transformer.
     *
     * @param Order $order
     * @return array
     */
    public function transform(Order $order)
    {
        return [
            'user' => $order->user,
            'reserve_time' => $order->reservedTimeDates,
            'massage' => $order->massage,
            'package' => $order->packages->name,
            'transaction'=> $order->transactions,
            'filled_form' => $order->filledForm,
            'times' => $order->times ,
        ];
    }

    public static function originalAttribute($index){
        $attributes =[
            'user' => 'user_id'
        ];
        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
