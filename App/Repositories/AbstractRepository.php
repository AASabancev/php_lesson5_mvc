<?php

namespace App\Repositories;

class AbstractRepository
{
    protected $model;

    function getModel($data)
    {
        return new $this->model($data);
    }
}
