<?php

namespace App\Services;

use App\Models\User;

class UserService extends AbstractService
{
    protected $model = User::class;

    public function saveUser(array $userData): int
    {
        $userId = $this->getModel()
            ->setFio($userData['fio'])
            ->setLogin($userData['login'])
            ->setPassword($userData['password'])
            ->create();

        return $userId;
    }
}
