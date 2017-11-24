<?php
namespace app\models;

class LoginHistory extends Model {
  public function __construct() {
      $this->table = 'login_history';
  }

  public function push($uid, $result) {
    $this->user_id = $uid;
    $this->attempt_time = date("Y-m-d H:i:s");
    $this->success = $result;

    if ($this->save()) {
      return true;
    }
    else {
      return false;
    }
  }
}

 ?>
