<?php

namespace App\Services;


use Illuminate\Database\Eloquent\Builder;

class AbstractService
{
    protected $model;

    function getModel(): Builder
    {
        return (new $this->model())->newQuery();
    }

}
