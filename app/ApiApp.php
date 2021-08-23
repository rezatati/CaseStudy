<?php

use Library\ErrorHandlers\DefaultErrorHandler;
use Library\Routing\RoutServiceProvider;

define("APP_ROOT", $_SERVER['DOCUMENT_ROOT'] . '/../');
$config = include "config.php";

class ApiApp
{
  public function run()
  {
    (new DefaultErrorHandler())->registerHandler();
    (new RoutServiceProvider())->run();
  }
}