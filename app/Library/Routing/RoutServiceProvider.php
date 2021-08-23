<?php

namespace App\Library\Routing;

class RoutServiceProvider
{
  private $routes = [];
  public function run()
  {
    $files = glob(APP_ROOT . "/Routes/*.php");
    foreach ($files as $file) {
      $this->extractRoutes($file);
    }
    $foundedRoute = $this->findRoute();
    if (!$foundedRoute) {
      $this->HandleNotFoundRoute();
    }
    $this->ServeRoute($foundedRoute);
  }
  private function ServeRoute($route)
  {
    $class = explode('::', $route['handler'])[0];
    $method = explode('::', $route['handler'])[1];
    (new $class)->$method();
  }
  private function  HandleNotFoundRoute()
  {
    header('application/html', true, 404);
    echo 'Page Not Found';
    exit;
  }
  private function findRoute()
  {
    $url = trim(strtolower($_SERVER['REQUEST_URI']), '/');
    $method = strtolower($_SERVER['REQUEST_METHOD']);
    if (isset($this->routes[$method])) {
      foreach ($this->routes[$method] as $route) {
        if ($route['url'] == $url) {
          return $route;
        }
      }
    }
    return null;
  }
  private function extractRoutes($filePath)
  {
    $prefix = str_replace('-', '/', str_replace('.php', '', pathinfo($filePath)['basename']));
    $routesArray = include $filePath;
    foreach ($routesArray as $key => $route) {
      $method = strtolower($route['method']);
      if (!isset($this->routes[$method])) {
        $this->routes[$method] = [];
      }
      $this->routes[$method][] = ['url' => str_replace('//', '/', trim(strtolower("/$prefix/$key"), '/')), 'handler' => $route['handler']];
    }
  }
}