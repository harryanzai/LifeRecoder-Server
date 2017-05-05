<?php

namespace App\Policies;


use App\Models\Gallery;
use App\Models\Photo;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PhotoPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }


    public function update(User $user,Photo $photo)
    {

        return $user->id === $photo->gallery->user_id;

    }


}
