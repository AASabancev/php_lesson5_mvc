<?php

namespace App\Http\Controllers;

use App\Http\Requests\Request;
use App\Mail\RegisterMail;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\UserService;

class UserController extends AbstractController
{

    protected $userService;
    protected $userRepository;

    public function __construct()
    {
        $this->userService = new UserService();
        $this->userRepository = new UserRepository();
    }

    function logout(Request $request)
    {
        $request->unsetUser();
        $this->redirect('/user/login');
    }

    function login(Request $request)
    {
        $errorMessage = null;
        $data = $request->only(['login', 'password']);

        if (empty($data['login'])) {
            // Предпочитаю выходить как можно раньше из функций!
            return $this->view->render('Auth/login.phtml');
        }

        if (empty($data['password'])) {
            $errorMessage = 'Введите логин и пароль';
        }

        if (!$errorMessage) {
            $user = $this->userRepository
                ->findByLogin($data['login']);

            if (!$user || $user->password != User::hashPassword($data['password'])) {
                $errorMessage = 'Логин или пароль не верный :(';
            }
        }

        if (!empty($errorMessage)) {
            return $this->view->render('Auth/login.phtml', [
                'message' => $errorMessage,
            ]);
        }

        $request->setUser($user->id);
        $this->redirect('/blog/index');
    }

    function register(Request $request)
    {
        $data = $request->only([
            'fio',
            'login',
            'password',
            'password_confirmation',
        ]);

        if (empty($data['login'])) {
            // Предпочитаю выходить как можно раньше из функций!
            return $this->view->render('Auth/register.phtml');
        }

        $existUser = $this->userRepository
            ->findByLogin($data['login']);


        $errorMessage = null;
        if (!empty($existUser)) {
            $errorMessage = "Логин уже занят!";
        } else if (empty($data['fio'])) {
            $errorMessage = "Введите пожалуйста ФИО";
        } else if (!filter_var($data['login'], FILTER_VALIDATE_EMAIL)) {
            $errorMessage = "Введите верный Email";
        } elseif (empty($data['password'])) {
            $errorMessage = "Введите пожалуйста Пароль";
        } elseif (mb_strlen($data['password']) < 4) {
            $errorMessage = "Пароль должен быть не менее 4 символов";
        } elseif ($data['password'] != $data['password_confirmation']) {
            $errorMessage = "Пароли не совпадают";
        }

        if (!empty($errorMessage)) {
            return $this->view->render('Auth/register.phtml', [
                'message' => $errorMessage,
                'fio' => $data['fio'] ?? null,
                'login' => $data['login'] ?? null,
            ]);
        }

        $user = $this->userService
            ->saveUser($data);

        $request->setUser($user->id);

        $mail = new RegisterMail();
        $mail->sendMail($data['login']);

        $this->redirect('/blog/index');
    }

    function index(Request $request)
    {
        return $this->view
            ->render('Auth/register.phtml');
    }

    function update(Request $request)
    {
        $this->checkAuth($request, [User::ROLE_ADMIN]);

        $id = $request->get('id');
        $data = $request->only(['fio','login','password']);
        $image = $request->getFile('image');

        $this->userService
            ->updateUser($id, $data, $image);

        $this->redirect('/twig/users');
    }

    function create(Request $request)
    {
        $this->checkAuth($request, [User::ROLE_ADMIN]);

        $data = $request->only(['fio','login','password']);
        $image = $request->getFile('image');

        $this->userService
            ->saveUser($data, $image);

        $this->redirect('/twig/users');
    }

    function profile(Request $request)
    {
        $id = $request->get('id');

        return $this->view
            ->render('User/profile.phtml', [
                'user' => $this->userRepository->findById($id)
            ]);
    }

    function delete(Request $request)
    {
        $this->checkAuth($request, [User::ROLE_ADMIN]);
        $id = $request->get('id');

        $user = $this->userRepository
            ->findById($id);

        $user->deleteImage();
        $user->delete();

        $this->redirect('/twig/users');
    }

    function me(Request $request)
    {
        $id = $request->get('id');

        $user = $this->userRepository
            ->findById($id);

        return $user;
    }
}
