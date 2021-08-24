<?php

/**
 * load autoload file and utils and init db 
 */
require "vendor/autoload.php";
require_once "Library/utilis.php";

use Illuminate\Database\Capsule\Manager as Capsule;

global $config;
$config = include "config.php";
$capsule = new Capsule;
$capsule->addConnection([
  "driver" => "mysql",
  "host" => $config['db_host'],
  "database" => $config['db_name'],
  "username" => $config['db_user'],
  "password" => $config['db_pass']
]);
$capsule->setAsGlobal();
$capsule->bootEloquent();