<?php

namespace App;

use App\Library\ErrorHandlers\DefaultErrorHandler;
use App\Library\Routing\RoutServiceProvider;


define("APP_ROOT", $_SERVER['DOCUMENT_ROOT'] . '/../');
require_once APP_ROOT . "bootstrap.php";

class ApiApp
{
  public function run()
  {
    (new DefaultErrorHandler())->registerHandler();
    $this->initDb();
    (new RoutServiceProvider())->run();
  }
  private function initDb()
  {
    $files = glob(APP_ROOT . "/database/*.php");
    foreach ($files as $files) {
      require_once $files;
    }
  }
}