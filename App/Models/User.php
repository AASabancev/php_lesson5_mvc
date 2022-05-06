<?php

namespace App\Models;

use App\Models\Traits\ImageTrait;
use App\Observers\UserObserver;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use ImageTrait;

    protected static function boot()
    {
        parent::boot();
        self::observe(UserObserver::class);
    }

    const ROLE_ADMIN = 1;
    const ROLE_USER = 2;

    protected $table = 'users';
    protected $fillable = [
        'id',
        'role_id',
        'image',
        'fio',
        'login',
        'password',
        'created_at',
        'updated_at',
    ];

    protected $hidden = [
        'role_id',
        'password',
    ];

    public function isAdmin(): string
    {
        return $this->role_id == self::ROLE_ADMIN;
    }

    public static function hashPassword(string $password): string
    {
        return sha1(PWD_SALT . trim($password));
    }
}
