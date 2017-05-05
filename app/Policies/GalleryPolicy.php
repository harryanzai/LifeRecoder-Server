<?php

namespace App\Policies;

use App\Models\Gallery;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GalleryPolicy
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

    public function update(User $user,Gallery $gallery)
    {
        return $user->id === $gallery->user_id;
    }


}
