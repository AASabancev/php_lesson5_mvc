<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserService extends AbstractService
{
    protected $model = User::class;

    public function saveUser(array $userData, ?array $image): Model
    {
        $user = $this->getModel()
            ->create($userData);

        $user->uploadImage($image, false);
        $user->save();

        return $user;
    }

    public function updateUser(int $id, array $userData, ?array $image)
    {
        $user = $this->getModel()
            ->find($id);

        if (!$user) {
            return false;
        }

        $user->uploadImage($image, false);
        $user->update($userData);
    }
}
