<?php
namespace m4\m4cms\controllers\admin;

use m4\m4cms\controllers\api\Users as UsersApi;
use m4\m4mvc\helper\Request;
use m4\m4mvc\helper\Response;
use m4\m4mvc\helper\Session;

class Users extends UsersApi
{

  public function create ()
  {
    $this->data['roles'] = $this->model::$roles;
  }

  public function update ($id = null)
  {
    $this->data['user'] = $this->model->getById($id);
    $this->data['roles'] = $this->model::$roles;
  }

  public function createAjax ()
  {
    Request::forceMethod('post');
    Request::required('username', 'email', 'password', 'role');
    $data = Request::select('username', 'email', 'password', 'first_name', 'last_name', 'about_me', 'role');

    $this->validate($data);
    if ($this->model->exists($data['email'], $data['username'])) {
      Response::error('Username or email is already taken. ');
    }

    $id = $this->model->insert($data);
    $id ?
    Response::success('User was created ', ['id' => $id]) :
    Response::error('User was not created. ');
  }

  public function updateAjax ()
  {
    Request::forceMethod('post');
    Request::required('id', 'username', 'email', 'role');
    $data = Request::select('id', 'username', 'email', 'password', 'first_name', 'last_name', 'about_me', 'role');

    $this->validate($data);
    if ($this->model->exists($data['email'], $data['username'], $data['id'])) {
      Response::error('Username or email is already taken. ');
    }

    // do not allow demoting the last admin
    $current = $this->model->getById($data['id']);
    if ($current && $current['role'] == $this->model::ROLE_ADMIN
        && $data['role'] != $this->model::ROLE_ADMIN
        && $this->model->countAdmins() <= 1) {
      Response::error('You cannot demote the last admin. ');
    }

    $this->model->update($data) ?
    Response::success('User was updated ') :
    Response::error('User was not updated. ');
  }

  public function delete ()
  {
    Request::forceMethod('post');
    Request::required('id');
    $id = $_POST['id'];

    if ($id == Session::get('user_id')) {
      Response::error('You cannot delete your own account. ');
    }

    $user = $this->model->getById($id);
    if (!$user) {
      Response::error('User does not exist. ');
    }
    if ($user['role'] == $this->model::ROLE_ADMIN && $this->model->countAdmins() <= 1) {
      Response::error('You cannot delete the last admin. ');
    }

    $this->model->delete($id) ?
    Response::success('User was deleted') :
    Response::error('User could not be deleted. ');
  }

  public function login ()
  {
    if ($_POST) parent::login();
  }

  private function validate ($data)
  {
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
      Response::error('Email is not valid. ');
    }
    if (!isset(($this->model)::$roles[$data['role']])) {
      Response::error('Role is not valid. ');
    }
  }

}
