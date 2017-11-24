<?php
namespace app\controllers;

use app\controllers\UserController;
use app\controllers\auth\AuthController as Auth;

class GenericController extends Controller {

  public function index() {
    $user = Auth::user();

    if (!$user) {
      return $this->view('login');
    }
    else {
      $authenticated = new UserController();
      return $authenticated->index();
    }
  }
}

 ?>
