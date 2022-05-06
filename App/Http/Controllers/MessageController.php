<?php

namespace App\Http\Controllers;

use App\Helpers\FileHelper;
use App\Helpers\ImageMagick;
use App\Http\Requests\Request;
use App\Models\User;
use App\Repositories\MessageRepository;
use App\Services\MessageService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class MessageController extends AbstractController
{
    protected $messageRepository;
    protected $messageService;


    public function __construct()
    {
        $this->messageRepository = new MessageRepository();
        $this->messageService = new MessageService();
    }

    function new(Request $request)
    {
        $this->checkAuth($request);

        $data = $request->only(['text']);
        $image = $request->getFile('image');

        if (!empty($data['text'])) {

            $message = $this->messageService
                ->saveMessage([
                    'user_id' => $request->user->id,
                    'text' => $data['text'],
                ]);

            $message->uploadImage($image);
            $message->save();
        }

        $this->redirect('/blog/index' . ($message ? '?notice=' . urlencode('Сообщение отправлено!') : ''));
    }

    /**
     * это так, для себя проверял
     * @param Request $request
     * @return Model|null
     */
    function find(Request $request): ?Model
    {
        $this->checkAuth($request);

        $id = $request->get('id');

        $message = $this->messageRepository
            ->findById($id);

        return $message;
    }

    /**
     * Последние сообщения одного автора
     * @param Request $request
     * @return Collection
     */
    function author(Request $request): Collection
    {
        $this->checkAuth($request);
        $user_id = $request->get('user_id');

        $message = $this->messageRepository
            ->findByUserId($user_id);

        return $message;
    }

    /**
     * Список последних сообщений
     * @param Request $request
     * @return Collection
     */
    function index(Request $request): Collection
    {
        $messages = $this->messageRepository
            ->history();

        return $messages;
    }

    /**
     * Удаление одного сообщения (права: админ)
     * @param Request $request
     * @throws \App\Exceptions\RedirectLoginException
     */
    function delete(Request $request)
    {
        $this->checkAuth($request, [User::ROLE_ADMIN]);

        $id = $request->get('id');

        $message = $this->messageRepository
            ->findById($id);

        if ($message) {
            $notice = "Сообщение #" . $message->id . " успешно удалено";
            $message->deleteImage();
            $message->delete();
        } else {
            $notice = "Сообщение #" . $message->id . " не найдено";
        }

        $this->redirect('/blog/index?notice=' . urlencode($notice));
    }
}
