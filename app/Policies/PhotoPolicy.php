<?php

namespace App\Policies;

use App\Models\Photo;
use App\Models\User;

class PhotoPolicy
{
    /**
     * Determine whether the user can view the photo.
     */
    public function view(User $user, Photo $photo): bool
    {
        return $photo->user_id === $user->id;
    }

    /**
     * Determine whether the user can upload new photos.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can rename or re-favorite the photo.
     */
    public function update(User $user, Photo $photo): bool
    {
        return $photo->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the photo.
     */
    public function delete(User $user, Photo $photo): bool
    {
        return $photo->user_id === $user->id;
    }
}
