<?php

namespace App\Transformers;

use App\Offers;
use League\Fractal\TransformerAbstract;

class OfferTransformer extends TransformerAbstract
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
     * @param Offers $offer
     * @return array
     */
    public function transform(Offers $offer)
    {
        return [
            'code' => $offer->code,
            'date' => $offer->date,
            'validate' => $offer->validate,
            'offer' => $offer->offer,
        ];
    }
    public static function originalAttribute($index){
        $attributes =[
        ];
        return isset($attributes[$index]) ? $attributes[$index] : null;
    }

}
