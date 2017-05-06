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


    /**
     * @param User $user
     * @param Photo $photo
     * @return bool
     */
    public function update(User $user, Photo $photo)
    {
        return $user->id === $photo->gallery->user_id;
    }

    /**
     * @param User $user
     * @param Photo $photo
     * @return bool
     */
    public function destroy(User $user, Photo $photo)
    {
        return $user->id === $photo->gallery->user_id;
    }


}
