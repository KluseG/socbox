<?php
namespace app\controllers;

use app\controllers\UserController;
use app\controllers\auth\AuthController as Auth;
use app\models\Users;
use app\models\UserProfile;

class SearchController extends Controller {

  private $matches;

  public function __construct() {
    $this->matches = [];
    return $this->middleware('Auth');
  }

  public function findUserByName($fname, $lname) {
    $users = new UserProfile();
    $usersByFirstName = $users->where('first_name', $fname)->select('user_id as id, first_name, last_name')->get();

    $users = new UserProfile();
    $usersByLastName = $users->where('last_name', $lname)->select('user_id as id, first_name, last_name')->get();

    if (isset($usersByFirstName[0])) {
      foreach ($usersByFirstName as $key => $strength) {
        $id = $strength['id'];
        $count = 0;
        foreach ($this->matches as $key => $match) {
          if ($match['id'] == $id) {
            $this->matches[$key]['strength'] += 1;
            $count++;
          }
        }

        if (!$count) {
          $this->matches[] = [
            'id' => $strength['id'],
            'item' => $strength['first_name'] . ' ' . $strength['last_name'],
            'strength' => 1
          ];
        }
      }
    }
    elseif (isset($usersByFirstName['first_name'])) {
      $id = $usersByFirstName['id'];
      $count = 0;
      foreach ($this->matches as $key => $match) {
        if ($match['id'] == $id) {
          $this->matches[$key]['strength'] += 1;
          $count++;
        }
      }

      if (!$count) {
        $this->matches[] = [
          'id' => $usersByFirstName['id'],
          'item' => $usersByFirstName['first_name'] . ' ' . $usersByFirstName['last_name'],
          'strength' => 1
        ];
      }
    }

    if (isset($usersByLastName[0])) {
      foreach ($usersByLastName as $key => $strength) {
        $id = $strength['id'];
        $count = 0;
        foreach ($this->matches as $key => $match) {
          if ($match['id'] == $id) {
            $this->matches[$key]['strength'] += 1;
            $count++;
          }
        }

        if (!$count) {
          $this->matches[] = [
            'id' => $strength['id'],
            'item' => $strength['first_name'] . ' ' . $strength['last_name'],
            'strength' => 1
          ];
        }
      }
    }
    elseif (isset($usersByLastName['first_name'])) {
      $id = $usersByLastName['id'];
      $count = 0;
      foreach ($this->matches as $key => $match) {
        if ($match['id'] == $id) {
          $this->matches[$key]['strength'] += 1;
          $count++;
        }
      }

      if (!$count) {
        $this->matches[] = [
          'id' => $usersByLastName['id'],
          'item' => $usersByLastName['first_name'] . ' ' . $usersByLastName['last_name'],
          'strength' => 1
        ];
      }
    }

    return true;
  }

  public function findUserByUsername($uname) {
    $users = new Users();

    $usersByUsername = $users->where('username', $uname)
                             ->profile()
                             ->select('id, users_profile.first_name, users_profile.last_name')
                             ->get();

    if (isset($usersByUsername[0])) {
      foreach ($usersByUsername as $key => $strength) {
        $id = $strength['id'];
        $count = 0;
        foreach ($this->matches as $key => $match) {
          if ($match['id'] == $id) {
            $this->matches[$key]['strength'] += 1;
            $count++;
          }
        }

        if (!$count) {
          $this->matches[] = [
            'id' => $strength['id'],
            'item' => $strength['first_name'] . ' ' . $strength['last_name'],
            'strength' => 1
          ];
        }
      }
    }
    elseif (isset($usersByUsername['first_name'])) {
      $id = $usersByUsername['id'];
      $count = 0;
      foreach ($this->matches as $key => $match) {
        if ($match['id'] == $id) {
          $this->matches[$key]['strength'] += 1;
          $count++;
        }
      }

      if (!$count) {
        $this->matches[] = [
          'id' => $usersByUsername['id'],
          'item' => $usersByUsername['first_name'] . ' ' . $usersByUsername['last_name'],
          'strength' => 1
        ];
      }
    }

    return true;
  }

  public function fullSearch($request) {
    $string = $request['string'];
    $remember = $string;

    $string = explode(' ', $string);

    if (isset($string[1])) {
      $this->findUserByName($string[0], $stirng[1]);
      $this->findUserByName($string[1], $string[0]);
    }
    else {
      $this->findUserByName($string[0], '');
      $this->findUserByName('', $string[0]);
      $this->findUserByUsername($string[0]);
    }

    usort($this->matches, function($a, $b){
      return $b['strength'] - $a['strength'];
    });

    foreach ($this->matches as $key => $match) {
      $user = new Users();
      $user = $user->where('id', $match['id']);
      $profile = $user->profile();
      $avatar = $profile->avatar();

      $userAvatar = $avatar->get()['filename'];

      $this->matches[$key]['uname'] = $user->get()['username'];
      $this->matches[$key]['avatar'] = $userAvatar;
    }

    $currentUser = Auth::user();

    $profile = $currentUser->profile();
    $avatar = $profile->avatar();

    $userFirstName = $profile->get()['first_name'];
    $userLastName = $profile->get()['last_name'];
    $userAvatar = $avatar->get()['filename'];

    return $this->view('search', [
      'firstName' => $userFirstName,
      'lastName' => $userLastName,
      'avatar' => $userAvatar,
      'results' => $this->matches,
      'value' => $remember
    ]);
  }

  public function test() {
    $this->findUserByName('Kluska', 'Adrian');
    $this->findUserByName('Adrian', 'Kluska');
    $this->findUserByUsername('ziomek1');

    print('<pre>' . print_r($this->matches, true) . '</pre>');
  }
}

 ?>
