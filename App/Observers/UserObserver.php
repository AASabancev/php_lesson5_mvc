<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    public function creating(User $user)
    {
        $user->password = $user->hashPassword($user->password);
    }

    public function updating(User $user)
    {
        if ($user->isDirty('password')) {
            $user->password = $user->hashPassword($user->password);
        }
    }

    public function deleting(User $user)
    {
        $user->deleteImage();
    }
}