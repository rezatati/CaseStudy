<?php

use Library\ErrorHandlers\DefaultErrorHandler;
use Library\Routing\RoutServiceProvider;


define("APP_ROOT", $_SERVER['DOCUMENT_ROOT'] . '/../');
require APP_ROOT . "vendor/autoload.php";
global $config;
$config = include "config.php";
class ApiApp
{
  public function run()
  {
    (new DefaultErrorHandler())->registerHandler();
    (new RoutServiceProvider())->run();
  }
}