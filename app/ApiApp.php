<?php

use Library\Routing\RoutServiceProvider;

class ApiApp
{
  public function run()
  {
    (new RoutServiceProvider())->run();
  }
}