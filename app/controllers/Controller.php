<?php

namespace app\controllers;

/*
require_once 'Mail.php';
require_once 'Mail/mime.php';
*/

use vendor\view\View;

use vendor\router\Router;

use app\middleware\Middleware;

use Mail;
use Mail_mime;
use PEAR;

class Controller {

  protected function view($tpl, $vars = null) {
    return new View($tpl, $vars);
  }

  protected function middleware($name) {
    return new Middleware($name);
  }

  protected function redirect($redirectTo = null) {
    if ($redirectTo) {
        return header('Location: ' . $redirectTo);
    }
  }

  protected function mail($to, $subject, $message) {
    $from = '<no-reply@socbox.com>';
    $to = '<' . $to . '>';

    $driver = getenv('MAIL_DRIVER');
    $host = getenv('MAIL_HOST');
    $port = getenv('MAIL_PORT');
    $username = getenv('MAIL_USER');
    $password = getenv('MAIL_PASSWORD');

    $headers = [
      'From' => $from,
      'To' => $to,
      'Subject' => $subject
    ];

    $crlf = PHP_EOL;

    $mime = new Mail_mime([
      'eol' => $crlf,
      'head_charset' => 'utf-8',
      'text_charset' => 'utf-8',
      'html_charset' => 'utf-8',
    ]);

    $mime->setHTMLBody($message);

    $smtp = Mail::factory($driver, [
      'host' => $host,
      'port' => $port,
      'auth' => true,
      'username' => $username,
      'password' => $password
    ]);

    $mail = $smtp->send($to, $mime->headers($headers), $mime->get());

    if (PEAR::isError($mail)) {
      return $mail->getMessage();
    }
    else {
      return 'Ok';
    }
  }

}

 ?>
