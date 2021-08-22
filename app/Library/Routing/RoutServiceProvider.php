<?php

namespace Library\Routing;

class RoutServiceProvider
{
  private $routes = [];
  public function run()
  {
    var_dump($_SERVER['REQUEST_URI']);

    $files = glob(APP_ROOT . "/Routes/*.php");
    foreach ($files as $file) {
      $this->extractRoutes($file);
    }
    $foundedRoute = $this->findRoute();
  }
  private function findRoute()
  {
    $url = $_SERVER['REQUEST_URI'];
    $method = $_SERVER['REQUEST_METHOD'];
  }
  private function extractRoutes($filePath)
  {
    $prefix = str_replace('.php', '', pathinfo($filePath)['basename']);
    $routesArray = include $filePath;
    foreach ($routesArray as $key => $route) {
      if (!isset($this->routes[$route['type']])) {
        $this->routes[$route['type']] = [];
      }
      $this->routes[$route['type']] = ['url' => "/$prefix/$key", 'handler' => $route['handler']];
    }
  }
}