<?php


include './vendor/autoload.php';
require_once 'config.php';

session_start();

$app = new \Base\Application();
$app->run();
