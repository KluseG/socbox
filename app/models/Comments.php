<?php
namespace app\models;

class Comments extends Model {

  public function user() {
    return $this->hasOne('Users', 'id', 'user_id');
  }

}

 ?>
