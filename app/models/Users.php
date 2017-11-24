<?php
namespace app\models;

class Users extends Model {

  public function profile() {
    return $this->hasOne('UserProfile', 'user_id');
  }

  public function history() {
    return $this->hasOne('LoginHistory', 'user_id', 'id');
  }

  public function feeds($joinProfile) {
    if ($joinProfile) {
      return $this->hasMany('Feeds', 'UsersFeeds', 'user_id', 'feed_id', 'UserProfile');
    }
    else {
      return $this->hasMany('Feeds', 'UsersFeeds', 'user_id', 'feed_id');
    }
  }

  public function followings() {
    return $this->hasMany('Users', 'UsersFollows', 'user_id', 'followed_user_id');
  }

}

 ?>
