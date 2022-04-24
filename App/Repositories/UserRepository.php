<?php

namespace App\Repositories;

use App\Models\User;
use Base\Db;

class UserRepository extends AbstractRepository
{
    protected $model = User::class;

    public function findById(int $id)
    {
        $db = Db::getInstance();
        $query = "SELECT * FROM `users` WHERE `id` = :id";
        $row = $db->fetchOne($query, __METHOD__, ['id' => $id]);

        if (!$row) {
            return null;
        }

        $user = $this->getModel($row);

        return $user;
    }

    public function searchUsersIn(array $ids, array $select = ['*'])
    {
        $db = Db::getInstance();

        $questions = str_repeat("?,", count($ids) - 1) . "?";

        $query = "SELECT `" . join('`, `', $select) . "` FROM `users` WHERE `id` IN (" . $questions . ")";
        $users = $db->fetchAll($query, __METHOD__, $ids);

        if (!$users) {
            return null;
        }

        return $users;
    }

    public function findByLogin(string $login)
    {
        $db = Db::getInstance();
        $query = "SELECT * FROM `users` WHERE `login` = :login";
        $row = $db->fetchOne($query, __METHOD__, ['login' => trim($login)]);

        if (!$row) {
            return null;
        }

        $user = $this->getModel($row);


        return $user;
    }
}
