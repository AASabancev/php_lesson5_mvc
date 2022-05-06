<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;

class UserObserver
{
    public function updating(Model $model)
    {
        // TODO: Сюда никак не могу попасть
        var_dump(__METHOD__, '');
        exit();

        if ($model->isDirty('password')) {
            $model->password = $model->hashPassword($model->password);
        }
    }

    public function deleting(Model $model)
    {
        // TODO: Сюда никак не могу попасть
        var_dump(__METHOD__, '');
        exit();

        $model->deleteImage();
    }
}