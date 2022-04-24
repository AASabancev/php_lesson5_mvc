<?php

namespace App\Repositories;

use App\Models\Message;
use Base\Db;

class MessageRepository extends AbstractRepository
{
    protected $model = Message::class;

    public function findById(int $id)
    {
        $db = Db::getInstance();
        $query = "SELECT * FROM `messages` WHERE `id` = :id";
        $row = $db->fetchOne($query, __METHOD__, ['id' => $id]);

        if (!$row) {
            return null;
        }

        $message = $this->getModel($row);

        return $message;
    }


    public function findByUserId(int $user_id)
    {
        $db = Db::getInstance();
        $query = "SELECT * FROM `messages` WHERE `user_id` = :user_id ORDER BY `created_at` DESC LIMIT 20";
        $messages = $db->fetchAll($query, __METHOD__, [
            'user_id' => $user_id
        ]);

        if (!$messages) {
            return null;
        }

        return $messages;
    }

    public function history()
    {
        $db = Db::getInstance();
        $query = "SELECT * FROM `messages` ORDER BY `created_at` DESC LIMIT 20";
        $messages = $db->fetchAll($query, __METHOD__, []);

        if (!$messages) {
            return null;
        }

        return $messages;
    }
}
