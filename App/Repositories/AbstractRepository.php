<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder;

class AbstractRepository
{
    protected $model;

    function getModel(): Builder
    {
        return (new $this->model())->newQuery();
    }
}
