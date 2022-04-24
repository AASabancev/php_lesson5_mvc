<?php

namespace App\Services;

class AbstractService
{
    protected $model;

    function getModel()
    {
        return new $this->model();
    }
}
