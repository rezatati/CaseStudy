<?php

/**
 * application calss that handle app runnig  and register needed service providers  
 */

namespace App;

use App\Library\ErrorHandlers\DefaultErrorHandler;
use App\Library\Routing\RoutServiceProvider;


define("APP_ROOT", $_SERVER['DOCUMENT_ROOT'] . '/../');
require_once APP_ROOT . "bootstrap.php";

class ApiApp
{
  /**
   * run the application
   */
  public function run()
  {
    (new DefaultErrorHandler())->registerHandler();
    $this->initDb();
    (new RoutServiceProvider())->run();
  }

  /**
   * init data base tables and create them if not exist
   */
  private function initDb()
  {
    $files = glob(APP_ROOT . "/database/*.php");
    foreach ($files as $files) {
      require_once $files;
    }
  }
}