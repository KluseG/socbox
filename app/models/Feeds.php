<?php
namespace app\models;

class Feeds extends Model {

  public function comments() {
    $this->hasOne('Comments', 'user_id');
  }

}

 ?>
