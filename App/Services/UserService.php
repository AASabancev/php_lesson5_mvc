<?php

namespace App\Services;

use App\Helpers\FileHelper;
use App\Helpers\ImageMagick;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserService extends AbstractService
{
    protected $model = User::class;

    public function saveUser(array $userData, ?array $image): Model
    {
        $userData['password'] = User::hashPassword($userData['password']);

        $user = $this->getModel()
            ->create($userData);

        $user->uploadImage($image, false);
        $user->save();

        return $user;
    }

    public function updateUser(int $id, array $userData, ?array $image)
    {
        if(!empty($userData['password'])) {
            $userData['password'] = User::hashPassword($userData['password']);
        }

        $user = $this->getModel()
            ->find($id);

        if(!$user){
            return false;
        }


        $user->uploadImage($image, false);
        $user->update($userData);
    }
}
