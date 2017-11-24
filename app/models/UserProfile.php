<?php
namespace app\models;

class UserProfile extends Model {

  public function __construct() {
      $this->table = 'users_profile';
  }

  public function avatar() {
    return $this->hasOne('Media', 'id', 'avatar_id');
  }

}

 ?>
