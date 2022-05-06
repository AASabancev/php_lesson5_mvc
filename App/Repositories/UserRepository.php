<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class UserRepository extends AbstractRepository
{
    protected $model = User::class;

    public function findById(int $id): ?Model
    {
        $user = $this->getModel()
            ->find($id);

        return $user;
    }

    public function findByLogin(string $login): ?Model
    {
        $user = $this->getModel()
            ->where('login', $login)
            ->first();

        return $user;
    }

    public function list(array $select = null, int $limit = 50): Collection
    {
        $users = $this->getModel()
            ->when($select, function ($query) use ($select) {
                return $query->select($select);
            })
            ->orderBy('id')
            ->limit($limit)
            ->get();

        return $users;
    }

    public function randlomList(array $select = null, int $limit = 5): Collection
    {
        $users = $this->getModel()
            ->when($select, function ($query) use ($select) {
                return $query->select($select);
            })
            ->inRandomOrder()
            ->limit($limit)
            ->get();

        return $users;
    }
}
