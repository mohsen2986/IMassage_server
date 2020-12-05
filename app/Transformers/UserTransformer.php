<?php

namespace App\Transformers;

use App\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{

    /**
     * A Fractal transformer.
     *
     * @param User $user
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'id' => (int)$user->id ,
            'name' => $user->name ,
            'family'=> $user->family ,
            'phone'=> $user->phone ,
            'photo'=> $user->photo ,
            'gender'=> $user->gender ,
        ];
    }
}
