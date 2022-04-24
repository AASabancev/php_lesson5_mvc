<?php

namespace App\Models;

class User extends AbstractModel
{
    const ROLE_ADMIN = 1;
    const ROLE_USER = 2;

    protected string $table = 'users';

    protected array $fields = [
        'id' => null,
        'role_id' => null,
        'fio' => null,
        'login' => null,
        'password' => null,
        'created_at' => null,
        'updated_at' => null,
    ];

    protected array $hidden = [
        'role_id',
        'password',
    ];

    public function isAdmin(): string
    {
        return $this->getRole() == self::ROLE_ADMIN;
    }

    public function getRole(): string
    {
        return $this->fields['role_id'];
    }

    public function getFio(): string
    {
        return $this->fields['fio'];
    }

    public function setFio(string $fio): self
    {
        $this->fields['fio'] = trim($fio);
        return $this->setUpdatedAt();
    }

    public function getLogin(): string
    {
        return $this->fields['login'];
    }

    public function setLogin(string $login): self
    {
        $this->fields['login'] = trim($login);
        return $this->setUpdatedAt();
    }

    public function getPassword(): string
    {
        return $this->fields['password'];
    }

    public function setPassword(string $password): self
    {
        $this->fields['password'] = self::hashPassword($password);
        return $this->setUpdatedAt();
    }

    public static function hashPassword(string $password): string
    {
        return sha1(PWD_SALT . trim($password));
    }
}
