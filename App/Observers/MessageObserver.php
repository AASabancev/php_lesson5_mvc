<?php

namespace App\Observers;

use App\Models\Message;

class MessageObserver
{
    public function deleting(Message $message)
    {
        $message->deleteImage();
    }

}