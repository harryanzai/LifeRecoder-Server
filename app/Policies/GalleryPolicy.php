<?php

namespace App\Policies;

use App\Models\Gallery;
use App\Models\User;
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

    /**
     * @param User $user
     * @param Gallery $gallery
     * @return bool
     */
    public function update(User $user, Gallery $gallery)
    {
        return $user->id === $gallery->user_id;
    }

    /**
     * @param User $user
     * @param Gallery $gallery
     * @return bool
     */
    public function destroy(User $user, Gallery $gallery)
    {
        return $user->id === $gallery->user_id;
    }


}
