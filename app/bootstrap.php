<?php
require "vendor/autoload.php";

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;
$capsule->addConnection([
  "driver" => "mysql",
  "host" => $config['host'],
  "database" => $config['db_name'],
  "username" => $config['db_user'],
  "password" => $config['db_pass']
]);
$capsule->setAsGlobal();
$capsule->bootEloquent();