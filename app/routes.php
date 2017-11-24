<?php

namespace app;

use vendor\router\Router;
use app\middleware\Kernel;

class Routes {

  public function __construct() {
    new Kernel();

    $app = new Router([
      '/',
      '/register',
      '/register/check',
      '/login',
      '/login/check',
      '/logout',
      '/password/forgot',
      '/password/send',
      '/password/reset/{integer}',
      '/password/change',
      '/user/activate',
      '/user/settings',
      '/user/profile',
      '/user/profile/{string}',
      '/user/follow/{string}',
      '/post/add',
      '/post/comment',
      '/search',
      '/avatar/change'
    ]);

    //AUTH RELATED ROUTES

    $app->post('/register', 'auth\AuthController@register');
    $app->post('/register/check', 'auth\AuthController@checkRegister');
    $app->post('/login', 'auth\AuthController@login');
    $app->post('/login/check', 'auth\AuthController@checkUser');
    $app->get('/logout', 'auth\AuthController@logout');
    $app->get('/password/forgot', 'auth\AuthController@showPasswordResetForm');
    $app->post('/password/send', 'auth\AuthController@sendPasswordResetForm');
    $app->get('/password/reset/{integer}', 'auth\AuthController@resetPassword');
    $app->post('/password/change', 'auth\AuthController@changePassword');

    //APP ROUTES

    $app->get('/', 'GenericController@index');
    $app->post('/user/activate', 'UserController@activate');
    $app->get('/user/settings', 'UserController@showSettings');
    $app->get('/user/profile', 'UserController@showProfile');
    $app->post('/post/add', 'UserController@addPost');
    $app->post('/post/comment', 'UserController@commentPost');
    $app->get('/user/profile/{string}', 'UserController@externalProfile');
    $app->get('/user/follow/{string}', 'UserController@handleFollow');
    $app->post('/search', 'SearchController@fullSearch');
    $app->post('/avatar/change', 'UserController@changeAvatar');
  }

}

 ?>
