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

        return $this->view->render('Blog/index.phtml', [
            'user' => $request->user,
            'messages' => $messages,
            'notice' => $request->get('notice'),
        ]);
    }

}
