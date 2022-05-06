<?php

namespace App\Models;

use App\Models\Traits\ImageTrait;
use App\Observers\MessageObserver;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use ImageTrait;

    protected static function boot()
    {
        parent::boot();
        self::observe(MessageObserver::class);

        static::deleting(function (Model $model) {
            // TODO: Сюда никак не могу попасть
            var_dump(__METHOD__, '');
            exit();

            $model->deleteImage();
        });


    }

//    protected $table = 'messages';
    protected $fillable = [
        'id',
        'user_id',
        'text',
        'image',
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
