<?php

namespace App\Http\Controllers;

use App\Helpers\FileHelper;
use App\Helpers\ImageHelper;
use App\Http\Requests\Request;
use App\Models\Message;
use App\Models\User;
use App\Repositories\MessageRepository;

class MessageController extends AbstractController
{
    protected $messageRepository;


    public function __construct()
    {
        $this->messageRepository = new MessageRepository();
    }

    function new(Request $request)
    {
        $this->checkAuth($request);

        $data = $request->only(['text']);
        $file = $request->getFile('file');

        $notice = null;
        $fileurl = null;
        if (!empty($file)) {
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $checkAllowed = in_array($ext, ImageHelper::ALLOWED_TYPES);
            if (!$checkAllowed) {
                $notice = "Расширение [" . strtoupper($ext) . "] недопустимо";
            } else {
                $path = DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR;
                $filename = FileHelper::generatestring(15) . '.' . $ext;
                $filepath = $path . $filename;
                ImageHelper::resaveImage($file['tmp_name'], $ext, DOC_ROOT . $filepath);

                // костыль для винды :(
                $fileurl = str_replace('\\', '/', $filepath);
            }
        }


        if (empty($notice) && !empty($data['text'])) {
            $message = new Message([
                'user_id' => $request->user->getId(),
                'text' => $data['text'],
                'fileurl' => $fileurl,
            ]);
            $message->create();
        }

        $this->redirect('/blog/index' . ($notice ? '?notice=' . urlencode($notice) : ''));
    }

    /**
     * это так, для себя проверял
     * @param Request $request
     * @return mixed|null
     */
    function find(Request $request)
    {
        $this->checkAuth($request);

        $id = $request->get('id');

        $message = $this->messageRepository->findById($id);

        return $message;
    }

    /**
     * Последние сообщения одного автора
     * @param Request $request
     * @return array|null
     */
    function author(Request $request)
    {
        $this->checkAuth($request);
        $user_id = $request->get('user_id');

        $message = $this->messageRepository->findByUserId($user_id);

        return $message;
    }

    /**
     * Список последних сообщений
     * @param Request $request
     * @return array|null
     */
    function index(Request $request)
    {
        $messages = $this->messageRepository->history();

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

        $message = $this->messageRepository->findById($id);
        if ($message) {
            $notice = "Сообщение #" . $message->getId() . " успешно удалено";
            $message->delete();
        } else {
            $notice = "Сообщение #" . $message->getId() . " не найдено";
        }

        $this->redirect('/blog/index?notice=' . urlencode($notice));
    }
}
