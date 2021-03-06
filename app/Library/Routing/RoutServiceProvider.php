<?php

/**
 * routing engine : proccess routes and call related class and function  based on defined routes 
 * 
 */

namespace App\Library\Routing;

class RoutServiceProvider
{
  private $routes = [];
  /**
   * start engin  
   * 
 
   */
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
  /**
   * run related calss and  function of final route  
   * 
   * @param array $route final route
   */
  private function ServeRoute($route)
  {
    $class = explode('::', $route['handler'])[0];
    $method = explode('::', $route['handler'])[1];
    if (isset($route['params'])) {
      (new $class)->$method($route['params']);
      return;
    }
    (new $class)->$method();
  }
  /**
   * if requested url is not in route list this function return related response   
   * 
   */
  private function  HandleNotFoundRoute()
  {
    notFoundError(404, 'Page Not Found');
  }
  /**
   * parse requested url and try to find it in route list   
   * 
   */
  private function findRoute()
  {
    $url = trim(strtolower($_SERVER['REQUEST_URI']), '/');
    $method = strtolower($_SERVER['REQUEST_METHOD']);
    $uri = "";
    if (isset($this->routes[$method])) {
      foreach ($this->routes[$method] as $route) {

        if (isset($route['pattern'])) {
          if (preg_match($route['pattern'], $url)) {
            $uriSegments     = explode('/', $url);
            $route['params'] = (int) array_pop($uriSegments);
            $uri   =  implode('/', $uriSegments);
            if ($route['url'] == $uri) {
              return $route;
            }
          }
        }
        if ($route['url'] == $url) {
          return $route;
        }
      }
    }
    return null;
  }

  /**
   * extract all defined routes in a file   
   * 
   * @param string $filePath  path of route defination file  
   */
  private function extractRoutes($filePath)
  {
    $prefix = str_replace('-', '/', str_replace('.php', '', pathinfo($filePath)['basename']));
    $routesArray = include $filePath;
    foreach ($routesArray as $route) {
      $key = $route['url'];
      $method = strtolower($route['method']);
      if (!isset($this->routes[$method])) {
        $this->routes[$method] = [];
      }
      if (isset($route['pattern'])) {
        $this->routes[$method][] = ['pattern' => $route['pattern'], 'url' => str_replace('//', '/', trim(strtolower("/$prefix/$key"), '/')), 'handler' => $route['handler']];
      } else {
        $this->routes[$method][] = ['url' => str_replace('//', '/', trim(strtolower("/$prefix/$key"), '/')), 'handler' => $route['handler']];
      }
    }
  }
}