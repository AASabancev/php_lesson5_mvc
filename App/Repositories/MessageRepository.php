<?php

namespace App\Repositories;

use App\Models\Message;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class MessageRepository extends AbstractRepository
{
    protected $model = Message::class;

    public function findById(int $id): ?Model
    {
        $message = $this->getModel()
            ->find($id);

        return $message;
    }

    public function findByUserId(int $user_id): Collection
    {
        $messages = $this->getModel()
            ->where('user_id', $user_id)
            ->get();

        return $messages;
    }

    public function history(): Collection
    {
        $messages = $this->getModel()
            ->with('user')
            ->orderByDesc('created_at')
            ->limit(20)
            ->get();

        return $messages;
    }
}
