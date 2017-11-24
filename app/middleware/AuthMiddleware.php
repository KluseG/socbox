<?php
namespace app\middleware;

use app\controllers\auth\AuthController as Auth;

class AuthMiddleware extends Middleware {

  function __construct() {
    if (!Auth::user()) {
      header('Location: /');
      die();
    }
  }
}


 ?>
