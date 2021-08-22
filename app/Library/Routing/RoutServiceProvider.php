<?php

namespace Library\Routing;

class RoutServiceProvider
{
  public function run()
  {

    $files = glob(APP_ROOT . "/Routes/*.php");
    foreach ($files as $file) {
      var_dump(pathinfo($file)['basename']);
    }
  }
  private function extractRoutes()
  {
  }
}