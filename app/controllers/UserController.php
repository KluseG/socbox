<?php
namespace app\controllers;

use app\models\Users;
use app\controllers\auth\AuthController as Auth;
use app\models\Feeds as Feed;
use app\models\UsersFeeds as UserFeed;
use app\models\Media;
use app\models\UsersFollows;
use app\models\Comments;
use Imagick;

class UserController extends Controller {

  public function __construct() {
    $this->middleware('Auth');
  }

  public function index() {
    $user = Auth::user();

    $posts = $this->getPosts($user->get()['id']);

    $followings = $user->followings()
                       ->select('users.id')
                       ->get();

    $followedActive = [];

    if ($followings) {
      if (isset($followings[0])) {
        foreach ($followings as $follow) {

          $followed = new Users();
          $media = new Media();

          $usr = $followed->where('id', $followings['id'])
                          ->select('last_login');
          $prfl = $usr->profile()
                      ->select('first_name, last_name, avatar_id')
                      ->get();
          $av = $media->where('id', $prfl['avatar_id'])
                      ->select('filename')
                      ->get()['filename'];
          $active = (time() + 3600) - strtotime($usr->get()['last_login']) > (3*60) ? $this->niceTime($usr->get()['last_login']) : 'active';

          $followedActive[] = [
            'fname' => $prfl['first_name'],
            'lname' => $prfl['last_name'],
            'avatar' => $av,
            'active' => $active
          ];

          $followedPosts[] = $this->getPosts($follow['id']);
        }
      }
      else {
        $followed = new Users();
        $media = new Media();

        $usr = $followed->where('id', $followings['id'])
                        ->select('last_login');
        $prfl = $usr->profile()
                    ->select('first_name, last_name, avatar_id')
                    ->get();
        $av = $media->where('id', $prfl['avatar_id'])
                    ->select('filename')
                    ->get()['filename'];
        $active = (time() + 3600) - strtotime($usr->get()['last_login']) > (3*60) ? $this->niceTime($usr->get()['last_login']) : 'active';

        $followedActive[] = [
          'fname' => $prfl['first_name'],
          'lname' => $prfl['last_name'],
          'avatar' => $av,
          'active' => $active
        ];

        $tmp = $this->getPosts($followings['id']);

        foreach ($tmp as $post) {
          $posts[] = $post;
        }
      }
    }

    if (isset($posts[0])) {
      foreach ($posts as $post) {
        $media = new Media();

        $avatar = $media->where('id', $post['avatar_id'])->get()['filename'];

        $viewPosts[] = [
          'id' => $post['id'],
          'body' => $post['body'],
          'created' => $this->niceTime($post['created_at']),
          'time' => strtotime($post['created_at']),
          'uname' => $post['username'],
          'ufname' => $post['first_name'],
          'ulname' => $post['last_name'],
          'avatar' => $avatar,
          'canEdit' => Auth::user()->get()['id'] == $post['uid'],
          'commentsCount' => is_array($post['comments']) ? count($post['comments']) : 0,
          'comments' => is_array($post['comments']) ? $post['comments'] : false
        ];
      }
    }
    else {
      $media = new Media();

      $avatar = $media->where('id', $posts['avatar_id'])->get()['filename'];

      $viewPosts[] = [
        'id' => $post['id'],
        'body' => $posts['body'],
        'created' => $this->niceTime($posts['created_at']),
        'time' => strtotime($posts['created_at']),
        'uname' => $posts['username'],
        'ufname' => $posts['first_name'],
        'ulname' => $posts['last_name'],
        'avatar' => $avatar,
        'canEdit' => Auth::user()->get()['id'] == $posts['uid'],
        'commentsCount' => is_array($post['comments']) ? count($post['comments']) : 0,
        'comments' => is_array($post['comments']) ? $post['comments'] : false
      ];
    }

    usort($viewPosts, function($a, $b) {
      return $b['time'] - $a['time'];
    });

    $user = $user->get();

    if (!$user['activated']) {
      return $this->view('activation', [
        'name' => $user['username']
      ]);
    }
    else {
      $user = Auth::user();
      $profile = $user->profile();
      $avatar = $profile->avatar();

      $userFirstName = $profile->get()['first_name'];
      $userLastName = $profile->get()['last_name'];
      $userAvatar = $avatar->get()['filename'];

      return $this->view('app', [
        'firstName' => $userFirstName,
        'lastName' => $userLastName,
        'avatar' => $userAvatar,
        'posts' => $viewPosts,
        'followedActive' => $followedActive
      ]);
    }
  }

  public function activate($request) {
    $user = Auth::user();
    $userArr = $user->get();

    $code = $userArr['activation_code'];

    if ($request['code'] == $code) {
      $user->set('activated', 1)
           ->update();

      echo 'true';
      die();
    }
    else {
      echo 'false';
      die();
    }
  }

  public function showSettings() {
    $user = Auth::user();

    $userArr = $user->profile()->get();
    $avatar = $user->profile()->avatar()->get();

    return $this->view('settings', [
      'firstName' => $userArr['first_name'],
      'lastName' => $userArr['last_name'],
      'avatar' => $avatar['filename'],
    ]);
  }

  public function showProfile() {
    $user = Auth::user();

    $userArr = $user->profile()->get();
    $userAvatar = $user->profile()->avatar()->get();

    $posts = $this->getPosts($user->get()['id']);

    if (isset($posts[0])) {
      foreach ($posts as $post) {
        $media = new Media();

        $avatar = $media->where('id', $post['avatar_id'])->get()['filename'];

        $viewPosts[] = [
          'body' => $post['body'],
          'created' => $this->niceTime($post['created_at']),
          'time' => strtotime($post['created_at']),
          'uname' => $post['username'],
          'ufname' => $post['first_name'],
          'ulname' => $post['last_name'],
          'avatar' => $avatar,
          'canEdit' => Auth::user()->get()['id'] == $post['uid']
        ];
      }
    }
    else {
      $media = new Media();

      $avatar = $media->where('id', $posts['avatar_id'])->get()['filename'];

      $viewPosts[] = [
        'body' => $posts['body'],
        'created' => $this->niceTime($posts['created_at']),
        'time' => strtotime($posts['created_at']),
        'uname' => $posts['username'],
        'ufname' => $posts['first_name'],
        'ulname' => $posts['last_name'],
        'avatar' => $avatar,
        'canEdit' => Auth::user()->get()['id'] == $posts['uid']
      ];
    }

    return $this->view('profile', [
      'firstName' => $userArr['first_name'],
      'lastName' => $userArr['last_name'],
      'avatar' => $userAvatar['filename'],
      'posts' => $viewPosts
    ]);
  }

  public function externalProfile($uname) {
    $user = new Users();
    $user = $user->where('username', $uname);

    if ($user->get()['id'] == Auth::user()->get()['id']) {
      return $this->showProfile();
    }

    $userArr = $user->profile()->get();
    $userAvatar = $user->profile()->avatar()->get();

    $posts = $this->getPosts($user->get()['id']);

    if (isset($posts[0])) {
      foreach ($posts as $post) {
        $media = new Media();

        $avatar = $media->where('id', $post['avatar_id'])->get()['filename'];

        $viewPosts[] = [
          'body' => $post['body'],
          'created' => $this->niceTime($post['created_at']),
          'time' => strtotime($post['created_at']),
          'uname' => $post['username'],
          'ufname' => $post['first_name'],
          'ulname' => $post['last_name'],
          'avatar' => $avatar,
          'canEdit' => Auth::user()->get()['id'] == $post['uid']
        ];
      }
    }
    else {
      $media = new Media();

      $avatar = $media->where('id', $posts['avatar_id'])->get()['filename'];

      $viewPosts[] = [
        'body' => $posts['body'],
        'created' => $this->niceTime($posts['created_at']),
        'time' => strtotime($posts['created_at']),
        'uname' => $posts['username'],
        'ufname' => $posts['first_name'],
        'ulname' => $posts['last_name'],
        'avatar' => $avatar,
        'canEdit' => Auth::user()->get()['id'] == $posts['uid']
      ];
    }

    $follow = new UsersFollows();
    $checkFollowing = $follow->where('user_id', Auth::user()->get()['id'])
                             ->where('followed_user_id', $user->get()['id']);

    $isFollowing = $checkFollowing->get() ? true : false;

    return $this->view('profile', [
      'username' => $uname,
      'following' => $isFollowing,
      'firstName' => $userArr['first_name'],
      'lastName' => $userArr['last_name'],
      'avatar' => $userAvatar['filename'],
      'posts' => $viewPosts
    ]);
  }

  public function handleFollow($request) {
    $uname = str_replace('?', '', $request);

    $user = new Users();
    $userToFollow = $user->where('username', $uname)
                         ->select('id')
                         ->get();
    $currentUser = Auth::user()->get()['id'];

    if ($userToFollow) {
      $follow = new UsersFollows();

      $checkFollowing = $follow->where('user_id', $currentUser)
                               ->where('followed_user_id', $userToFollow['id']);

      $isFollowing = $checkFollowing->get() ? true : false;


      if (!$isFollowing) {
        $follow->user_id = $currentUser;
        $follow->followed_user_id = $userToFollow['id'];
        $follow->save();
      }
      else {
        $checkFollowing->delete();
      }
    }

    return $this->redirect('/user/profile/'.$uname);
  }

  public function addPost($request) {
    $body = strlen(trim(strip_tags($request['body']))) > 0 ? trim(strip_tags($request['body'])) : false;

    if ($body) {
      $user = Auth::user();

      $post = new Feed();
      $post->body = $body;
      $post->save();

      $relation = new UserFeed();
      $relation->user_id = $user->currentId;
      $relation->feed_id = $post->currentId;

      $relation->save();
    }

    return $this->redirect('/');
  }

  public function commentPost($request) {
    $body = strlen(strip_tags($request['body'])) > 0 ? strip_tags($request['body']) : false;
    $cid = $request['postId'];

    if ($body) {
      $comment = new Comments();

      $comment->body = $body;
      $comment->user_id = Auth::user()->get()['id'];
      $comment->feed_id = $cid;
      $comment->save();
    }

    return $this->redirect('/');
  }

  public function changeAvatar($request) {
    $user = Auth::user();

    $target_dir = 'assets/img/';
    $filename = $user->get()['id'] . '_' . date('Y-m-d_H:i:m', time() + 3600) . '_' . basename($_FILES['avatar']['name']);
    $target_file = $target_dir . $filename;
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
    $check = getimagesize($_FILES['avatar']['tmp_name']);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        $uploadOk = 0;
    }
    if (file_exists($target_file)) {
      $uploadOk = 0;
    }
    if ($_FILES['avatar']["size"] > 500000) {
      $uploadOk = 0;
    }
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
      $uploadOk = 0;
    }
    if ($uploadOk == 0) {
      return $this->redirect('/');
    } else {
      if (move_uploaded_file($_FILES['avatar']["tmp_name"], $target_file)) {

        $thumb = new Imagick($target_file);

        $w = $thumb->getImageWidth();
        $h = $thumb->getImageHeight();

        $new_w = 256;
        $new_h = 256;

        if ($w > $h) {
          $resize_w = $w * $new_h / $h;
          $resize_h = $new_h;
        }
        else {
          $resize_w = $new_w;
          $resize_h = $h * $new_w / $w;
        }

        $thumb->resizeImage($resize_w, $resize_h, Imagick::FILTER_LANCZOS, 0.9);
        $thumb->cropImage($new_w, $new_h, ($resize_w - $new_w) / 2, ($resize_h - $new_h) / 2);

        $thumb->writeImage($target_file);

        $thumb->destroy();

          $media = new Media();
          $media->filename = $filename;
          $media->save();

          $user->profile()->set('avatar_id', $media->currentId)->update();



          return $this->redirect('/user/profile');
      }
      else {
          return $this->redirect('/');
      }
    }
  }

  private function getPosts($uid) {
    $user = new Users();

    $posts = $user->where('id', $uid)
                  ->feeds(true)
                  ->select(
                    'feeds.id
                    ,feeds.created_at
                    ,feeds.body
                    ,users.id as uid
                    ,users.username
                    ,users_profile.first_name
                    ,users_profile.last_name
                    ,users_profile.avatar_id'
                  )
                  ->orderBy('feeds.created_at', 'DESC')
                  ->get();

    foreach ($posts as $key => $post) {
      $comm = new Comments();

      $comments = $comm->where('feed_id', $post['id'])
                       ->select('id, user_id, body, created_at')
                       ->get();

      if (isset($comments[0])) {
        foreach ($comments as $ckey => $comment) {
          $comments[$ckey]['created_at'] = $this->niceTime($comments[$ckey]['created_at']);

          $user = new Users();
          $user = $user->where('id', $comment['user_id']);

          $comments[$ckey]['author'] = $user->select('id, username')
                                            ->get();
          $comments[$ckey]['author'] += $user->profile()
                                            ->select('first_name, last_name, avatar_id as avatar')
                                            ->get();
          $media = new Media();
          $avatar = $media->where('id', $comments[$ckey]['author']['avatar'])->get()['filename'];

          $comments[$ckey]['author']['avatar'] = $avatar;
        }
      }
      elseif (isset($comments['id'])) {
        $comments['created_at'] = $this->niceTime($comments['created_at']);

        $user = new Users();
        $user = $user->where('id', $comments['user_id']);

        $comments['author'] = $user->select('id, username')
                                    ->get();
        $comments['author'] += $user->profile()
                                    ->select('first_name, last_name, avatar_id as avatar')
                                    ->get();
        $media = new Media();
        $avatar = $media->where('id', $comments['author']['avatar'])->get()['filename'];

        $comments['author']['avatar'] = $avatar;

        $tmp = $comments;
        $comments = [];
        $comments[] = $tmp;
      }
      else {
        $comments = [];
      }

      $posts[$key]['comments'] = $comments;
    }

    return $posts;

  }

  private function niceTime($date) {
    if(empty($date)) {
        return "No date provided";
    }

    $periods         = array("s", "m", "h", "d", "w", "m", "y", "d");
    $lengths         = array("60","60","24","7","4.35","12","10");

    $now             = time() + 3600;
    $unix_date         = strtotime($date);

       // check validity of date
    if(empty($unix_date)) {
        return "Bad date";
    }

    // is it future date or past date
    if($now > $unix_date) {
        $difference     = $now - $unix_date;

        if ($difference < 60) {
          $difference = '';
          $tense = 'Just now';
        }
        else {
          $tense         = "";
        }

    } else {
        $difference     = '';
        $tense         = "Just now";
    }

    if ($tense !== 'Just now') {
      for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
          $difference /= $lengths[$j];
      }

      $difference = round($difference);

      if($difference != 1) {
          $periods[$j].= "";
      }

      return "$difference$periods[$j]{$tense}";
    }

    return "$difference{$tense}";
  }
}

 ?>
