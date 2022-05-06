<?php

namespace Base;

use Illuminate\Database\Capsule\Manager;

class Database {
  function __construct() {
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

    $capsule->bootEloquent();
  }
}