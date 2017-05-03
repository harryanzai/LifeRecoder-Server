<?php

namespace App\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    public function transform(User $user)
    {
        return [
            'id' => $user->id,
            'nickname' => $user->nickname,
            'email' => $user->email,
            'avatar' => url($user->avatar),
            'gender' => $user->gender,
        ];
    }

}