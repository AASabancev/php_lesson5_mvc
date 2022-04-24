<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Repositories\UserRepository;

class Request
{
    public ?User $user = null;
    private array $fields = [];
    private array $files = [];

    public function __construct()
    {
        $this->fields = $_REQUEST;
        $this->files = $_FILES;

        $this->initUser();
    }

    /**
     * Общий метод авторизации, записываем в реквест необходимые данные о пользователе
     * @param int $userId ID пользователя
     */
    private function initUser()
    {
        if (empty($_SESSION['user_id'])) {
            return;
        }

        $userRepository = new UserRepository();
        $user = $userRepository->findById($_SESSION['user_id']);
        if (!$user) {
            return;
        }

        $this->user = $user;
    }

    /**
     * Общий метод логаута, все очистки делаем тут
     */
    public function unsetUser()
    {
        session_destroy();
    }

    public function setUser(int $userId)
    {
        $_SESSION['user_id'] = $userId;
    }

    public function all()
    {
        return $this->fields;
    }

    public function get($key)
    {
        return $this->fields[$key] ?? null;
    }

    public function only(array $keys = [])
    {
        $result = [];
        foreach ($keys as $key) {
            if (empty($this->fields[$key])) {
                continue;
            }

            $result[$key] = $this->fields[$key] ?? null;
        }
        return $result;
    }

    public function getFiles()
    {
        return $this->files;
    }

    public function getFile(?string $key = null)
    {
        return $this->files[$key] ?? null;
    }
}
