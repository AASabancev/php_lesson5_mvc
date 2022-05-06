<?php

namespace App\Http\Controllers;

use App\Http\Requests\Request;
use App\Models\User;
use App\Repositories\UserRepository;

class TwigController extends AbstractController
{

    protected $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    function users(Request $request)
    {
        $this->checkAuth($request, [User::ROLE_ADMIN]);

        $users = $this->userRepository->list();

        return $this->view->render('Twig/users.twig', [
            'users' => $users,
        ]);
    }

}
