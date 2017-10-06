<?php
namespace m4\m4cms\controllers\admin;

use m4\m4cms\controllers\api\Users as UsersApi;
use m4\m4mvc\helper\Request;
use m4\m4mvc\helper\Response;

class Users extends UsersApi
{

  public function update ()
  {
    Request::forceMethod('post');
    Request::required('id');

    $data = Request::select('id', 'username', 'email', 'first_name', 'last_name', 'about_me', 'role');

    $this->model->update($data) ? 
    Response::success('User was updated ') : 
    Response::error('User was not updated. ');

  }

  public function create ()
  {
      Request::forceMethod('post');
      Request::required('username', 'email', 'password', 'role');
      $data = Request::select('username', 'email', 'password', 'role');

      $id = $this->model->insert($data);
      $id ? 
      Response::success('User was created ', ['id' => $id]) : 
      Response::error('User was not created. ');
  }

  public function login () {
    if ($_POST) self::login();
  }

}
