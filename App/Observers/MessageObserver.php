<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;

class MessageObserver
{
    public function deleting(Model $model)
    {
        // TODO: Сюда никак не могу попасть
        var_dump(__METHOD__, '');
        exit();

        $model->deleteImage();
    }

}