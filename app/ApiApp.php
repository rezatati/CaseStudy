<?php

use Library\ErrorHandlers\DefaultErrorHandler;
use Library\Routing\RoutServiceProvider;

define("APP_ROOT", $_SERVER['DOCUMENT_ROOT'] . '/../');

class ApiApp
{
  public function run()
  {
    (new DefaultErrorHandler())->registerHandler();
    (new RoutServiceProvider())->run();
  }
}