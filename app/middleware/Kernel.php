<?php
namespace app\middleware;

use app\middleware\Middleware;

class Kernel {

  private $run;

  public function __construct() {

    $this->run = [
      'UserActive'
    ];

    foreach ($this->run as $mw) {
      new Middleware($mw);
    }
  }
}

 ?>
