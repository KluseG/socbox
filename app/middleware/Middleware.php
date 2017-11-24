<?php
namespace app\middleware;

use app\middleware\AuthMiddleware;

class Middleware {
  public function __construct($n) {
    $mw = 'app\\middleware\\'.$n.'Middleware';

    return new $mw;
  }
}

 ?>
