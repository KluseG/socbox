<?php

namespace vendor\router;

require '../autoload.php';

use vendor\view\View;

class Router {

  private $routes;
  private $currentView = null;

  public function __construct($paths) {
    $this->routes = [];

    foreach ($paths as $key => $route) {
      $this->routes[] = $route;
    }
  }

  public function get($route, $action) {
    if (in_array($_SERVER['REQUEST_URI'], $this->routes) OR $this->isRouteWithParameter($_SERVER['REQUEST_URI'])) {
      if ($route == $_SERVER['REQUEST_URI'] || $this->hasParam($route)) {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
          if (gettype($action) == 'string') {
            $controllerName = substr($action, 0, strpos($action, '@'));
            $controller = "app\\controllers\\".$controllerName;
            $method = substr($action, strpos($action, '@') + 1);

            $request = new $controller;

            $param = $this->hasParam($route);

            if ($param) {
                return $request->$method($param);
            }
            else {
              return $request->$method();
            }
          }
          else if (is_callable($action)) {
            $param = $this->hasParam($route);

            if ($param) {
              return $action($param);
            }
            else {
              return $action();
            }
          }
          else {
            echo 'The action provided must be a function or controller method at route ' . $route;
          }
        }
        else {
          echo 'Method not allowed';
          exit;
        }
      }
      else {
        return false;
      }
    }
    else {
      if ($this->currentView !== '404') {
          $this->currentView = '404';
          return new View('404');
      }
      exit;
    }
  }

  public function post($route, $action) {
    if (in_array($_SERVER['REQUEST_URI'], $this->routes) OR $this->isRouteWithParameter($_SERVER['REQUEST_URI'])) {
      if ($route == $_SERVER['REQUEST_URI'] || $this->hasParam($route)) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
          if (gettype($action) == 'string') {
            $controllerName = substr($action, 0, strpos($action, '@'));
            $controller = "app\\controllers\\".$controllerName;
            $method = substr($action, strpos($action, '@') + 1);

            $request = new $controller;

            $param = $this->hasParam($route);

            if ($param) {
                return $request->$method($_POST, $param);
            }
            else {
              return $request->$method($_POST);
            }
          }
          else if (is_callable($action)) {
            $param = $this->hasParam($route);

            if ($param) {
              return $action($param);
            }
            else {
              return $action();
            }
          }
          else {
            echo 'The action provided must be a function or controller method at route ' . $route;
          }
        }
        else {
          echo 'Method not allowed';
          exit;
        }
      }
      else {
        return false;
      }
    }
    else {
      if ($this->currentView !== '404') {
          $this->currentView = '404';
          return new View('404');
      }
      exit;
    }
  }

  private function hasParam($route) {

    if (strpos($route, '{integer}') === FALSE && strpos($route, '{string}') === FALSE) {
      return false;
    }

    $hasParam = false;
    $serverUri = $_SERVER['REQUEST_URI'];

    $pos1 = strpos($route, '{');
    $left = substr($serverUri, $pos1);
    if (strpos($left, '/') !==  FALSE) {
      $right = substr($left, 0, strpos($left, '/'));
    }
    else {
      $right = $left;
    }

    if (substr($route, 0, $pos1) != substr($serverUri, 0, $pos1)) {
      return false;
    }

    $type = substr($route, strpos($route, '{') +1, strrpos($route, '}') - strpos($route, '{') - 1);

    if ($type == 'integer') {
      if (1 === preg_match('~[0-9]~', $right)){
          $hasParam = $right;
      }
      else {
        echo 'Given {parameter} ( '. $right .' ) is not type of ' . $type;
        exit;
      }
    }
    else if ($type == 'string') {
      if (1 === preg_match('~[a-z]~', $right)) {
        $hasParam = $right;
      }
      else {
        echo 'Given {parameter} ( '. $right .' ) is not type of ' . $type;
        exit;
      }
    }
    else {
      echo 'Given {parameter} must be type of integer or string';
      exit;
    }

    return $hasParam;

  }

  private function isRouteWithParameter($uri) {
    if ($uri == '/') {
      return false;
    }

    $url = explode('/', $uri);
    $routes = [];

    foreach ($this->routes as $route) {
      if (strpos($route, '{integer}') !== FALSE OR strpos($route, '{string}') !== FALSE) {
        $routes[] = explode('/', str_replace(array('{', '}'), '', $route));
      }
    }

    foreach ($routes as $route) {
      $matches = 0;

      foreach ($route as $key => $word) {
        if (array_key_exists($key, $url)) {
          if ($word != $url[$key]) {
            if ($word == 'integer') {
              if (1 === preg_match('~[0-9]~', $url[$key])){
                  $matches++;
                  $add = $url[$key];
                  $strKey = $key;
              }
            }
            else if ($word == 'string') {
              if (1 === preg_match('~[a-z]~', $url[$key])){
                  $matches++;
                  $add = $url[$key];
                  $strKey = $key;
              }
            }
          }
          else {
            $matches++;
          }
        }
      }

      if ($matches == count(explode('/', $_SERVER['REQUEST_URI']))) {
        return true;
      }
    }

    return false;
  }
}

 ?>
