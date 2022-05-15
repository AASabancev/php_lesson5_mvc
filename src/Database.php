<?php

namespace Base;

use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Events\Dispatcher;

class Database
{
    function __construct()
    {
        $capsule = new Manager();
        $capsule->addConnection([
            "driver" => DB_DRIVER,
            "host" => DB_HOST,
            "database" => DB_NAME,
            "username" => DB_USER,
            "password" => DB_PASSWORD,
            "charset" => "utf8",
            "collation" => "utf8_unicode_ci",
            "prefix" => "",
        ]);

        // Set the event dispatcher used by Eloquent models... (optional)
        $capsule->setEventDispatcher(new Dispatcher(new Container()));

        // Make this Capsule instance available globally via static methods... (optional)
        $capsule->setAsGlobal();

        // Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
        $capsule->bootEloquent();
    }
}