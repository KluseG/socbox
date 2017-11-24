<?php
namespace app\controllers\auth;

use app\controllers\Controller;
use app\models\Users;
use app\models\UserProfile as Profile;
use app\models\Feeds;
use app\models\LoginHistory as History;

session_start();
class AuthController extends Controller {

  public function register($request) {

    if (isset($_SESSION['app_id']) || !empty($_SESSION['app_id'])) {
        return $this->redirect('/');
    }

    $user = new Users();

    $user->email = $request['registerEmail'];
    $user->password = password_hash($request['registerPassword'], PASSWORD_BCRYPT);
    $user->username = $request['registerUsername'];
    $user->activation_code = str_pad(rand(0, pow(10, 4)-1), 4, '0', STR_PAD_LEFT);

    if (!$user->save()) {
        return $this->redirect('/');
    }

    $profile = new Profile();

    $profile->user_id = $user->currentId;
    $profile->avatar_id = 1;
    $profile->birth_date = date('Y-m-d', strtotime($request['registerDate']));
    $profile->first_name = $request['registerForename'];
    $profile->last_name = $request['registerSurname'];
    $profile->sex = $request['registerGender'];

    if(!$profile->save()) {
      return $this->redirect('/');
    }

    $this->sendVerificationCode($user->email, $user->activation_code, $user->username);

    $_SESSION['app_id'] = $user->currentId . uniqid();

    return $this->redirect('/');
  }

  public function checkRegister($request) {
    $users = new Users();

    $column = $request['check'];
    $value = $request['value'];

    $user = $users->select($column)
                  ->where($column, $value)
                  ->get();

    if ($user) {
      echo 'true';
      die();
    }
    else {
      echo 'false';
      die();
    }

    return;
  }

  public function login($request) {

    if (isset($_SESSION['app_id']) || !empty($_SESSION['app_id'])) {
      return $this->redirect('/');
    }

    $users = new Users();

    $user = $users->where('email', $request['email']);
    $userArr = $user->get();

    $history = new History();

    if ($userArr) {

      $dateCheck = date("Y-m-d H:i:s", strtotime('-15 minutes'));

      $loginHistory = $user->history()
                           ->where('attempt_time', $dateCheck, '>')
                           ->get();

      if ($loginHistory && count($loginHistory) >= 3) {
        $failedEntries = 0;
        foreach ($loginHistory as $entry) {
          if (!$entry['success']) {
            $failedEntries++;
          }
        }

        if ($failedEntries >= 3) {
          if ($userArr['activated'] == 1) {
            $newCode = str_pad(rand(0, pow(10, 4)-1), 4, '0', STR_PAD_LEFT);

            $to = $userArr['email'];
            $subject = 'SocBox account blocked';
            $htmlMessage = '<html><head><title>SocBox account blocked</title></head><body><h1>Your account has been blocked due to 3 invalid login attemps. To unlock your account please sign in with valid credentials and provide this code: '. $newCode .'</h1></body></html>';

            @$this->mail($to, $subject, $htmlMessage);

            $user->set('activated', 0)
                 ->set('activation_code', $newCode)
                 ->update();
          }

          return $this->view('error', [
            'message' => 'Your acount has been blocked. Check your email for further details and come back in 15 minutes.'
          ]);
        }
      }

      $pwd = $userArr['password'];
      if (password_verify($request['password'], $pwd)) {

        $history->push($userArr['id'], 1);

        $user->set('last_login', date("Y-m-d H:i:s"))
             ->update();

        $_SESSION['app_id'] = $userArr['id'] . uniqid();
      }
      else {
        $history->push($userArr['id'], 0);
      }
    }

    return $this->redirect('/');
  }

  public function logout() {

    $_SESSION = [];

    session_destroy();

    return $this->redirect('/');
  }

  public function checkUser($request) {
    $users = new Users();


    $email = $request['email'];
    $user = $users->where('email', $request['email']);

    $userArr = $user->get();

    if ($userArr['email']) {

      $avatar = $user->profile()
                     ->avatar()
                     ->get();

      echo $avatar['filename'];
      die();
    }
    else {
      echo 'false';
      die();
    }

    return;
  }

  public function sendVerificationcode($mail, $code, $uname) {
    $to = $mail;
    $subject = 'Your SocBox verification code';
    $htmlMessage = '<html><head><title>Your SocBox verification code</title></head><body><h1>Thank you for registering @'. $uname .'! Use this code in order to activate your account: '. $code .'</h1></body></html>';

    return $this->mail($to, $subject, $htmlMessage);
  }

  public function showPasswordResetForm() {
    return $this->view('pwdreset');
  }

  public function sendPasswordResetForm($request) {
    $users = new Users();
    $user = $users->where('email', $request['email'])->get();

    if($user) {
      echo 'true';

      $to = $user['email'];
      $subject = 'SocBox password reset';
      $link = '<a href="'. getenv('APP_URL') .'/password/reset/'. $this->encrypt($user['password']) .'">click here</a>';
      $htmlMessage = '<html><head><title>SocBox password reset</title></head><body><h1>To reset your password '. $link .'</h1></body></html>';

      return @$this->mail($to, $subject, $htmlMessage);
    }
    else {
      echo 'false';
    }

    return;
  }

  public function resetPassword($crypted) {
    $userOldPwd = $this->decrypt($crypted);

    $users = new Users();
    $user = $users->where('password', $userOldPwd);
    $email = $user->get()['email'];

    $newRandomPassword = $this->randomPassword();

    $user->set('password', password_hash($newRandomPassword, PASSWORD_BCRYPT))
         ->update();

    $to = $email;
    $subject = 'Your new SocBox password';
    $htmlMessage = '<html><head><title>Your new SocBox password</title></head><body><h1>Here is your new password: '. $newRandomPassword .'</h1></body></html>';

    @$this->mail($to, $subject, $htmlMessage);

    return $this->view('success', [
      'message' => "We've sent you your new password. You should change it as fast as possible."
    ]);
  }

  public function changePassword($request) {
    $user = self::user();

    $userPwd = $user->get()['password'];

    if (password_verify($request['oldPassword'], $userPwd)) {
      $user->set('password', password_hash($request['newPassword'], PASSWORD_BCRYPT))
           ->update();
    }

    return $this->redirect('/');
  }

  private function randomPassword() {
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = [];
    $alphaLength = strlen($alphabet) - 1;
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
  }

  private function encrypt($pure_string) {
    $encryption_key = getenv('APP_SECRET');
    $encrypted = str_replace('/', '*', openssl_encrypt($pure_string, "AES-128-ECB", $encryption_key));
    return $encrypted;
  }

  private function decrypt($encrypted_string) {
    $encryption_key = getenv('APP_SECRET');
    $decrypted = str_replace('*', '/', $encrypted_string);
    return openssl_decrypt($decrypted, "AES-128-ECB", $encryption_key);
  }

  public static function user() {
    if (!isset($_SESSION['app_id']) || empty($_SESSION['app_id'])) {
      return false;
    }

    $userid = substr($_SESSION['app_id'], 0, -13);

    $user = new Users();
    $user = $user->where('id', $userid);

    return $user;
  }
}

 ?>
