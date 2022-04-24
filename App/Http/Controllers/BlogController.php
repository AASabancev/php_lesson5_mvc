<?php

namespace App\Http\Controllers;

use App\Http\Requests\Request;
use App\Repositories\MessageRepository;
use App\Repositories\UserRepository;

class BlogController extends AbstractController
{
    protected $messageRepository;
    protected $userRepository;

    public function __construct()
    {
        $this->messageRepository = new MessageRepository();
        $this->userRepository = new UserRepository();
    }

    function index(Request $request)
    {
        $this->checkAuth($request);

        $messages = $this->messageRepository->history();
        $authorIDs = array_column($messages, 'user_id');
        $users = $this->userRepository->searchUsersIn($authorIDs, ['id', 'fio']);

        $authors = array_combine(
            array_column($users, 'id'),
            array_values($users)
        );

        return $this->view->render('Blog/index.phtml', [
            'user' => $request->user,
            'messages' => $messages,
            'authors' => $authors,
            'notice' => $request->get('notice'),
        ]);
    }

}
