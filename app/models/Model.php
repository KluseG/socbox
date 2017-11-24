<?php

namespace app\models;

use vendor\model\ModelWrapper;

class Model extends ModelWrapper {

  public function __construct() {
    $this->table = strtolower(str_replace('app\models\\', '', get_class($this)));
  }

}


?>
