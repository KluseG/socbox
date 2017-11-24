<?php
namespace app\middleware;

use app\controllers\auth\AuthController as Auth;

class UserActiveMiddleware extends Middleware {

  function __construct() {
    $user = Auth::user();

    if ($user) {
      $user->set('last_login', date('Y-m-d H:i:s', time() + 3600))
           ->update();
    }
  }
}


 ?>
