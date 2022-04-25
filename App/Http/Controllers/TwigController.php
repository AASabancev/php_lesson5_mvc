<?php

namespace App\Http\Controllers;

use App\Http\Requests\Request;
use App\Repositories\UserRepository;

class TwigController extends AbstractController
{

    protected $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    function test1(Request $request)
    {
        $users = $this->userRepository->randlomList();

        return $this->view->render('Twig/test1.twig', [
            'users' => $users,
        ]);
    }

}
