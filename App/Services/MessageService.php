<?php

namespace App\Services;

use App\Models\Message;
use Illuminate\Database\Eloquent\Model;

class MessageService extends AbstractService
{
    protected $model = Message::class;

    public function saveMessage(array $messageData): Model
    {
        $message = $this->getModel()
            ->create($messageData);

        return $message;
    }
}
