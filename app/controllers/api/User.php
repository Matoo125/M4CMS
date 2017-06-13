<?php
namespace m4\m4cms\controllers\api;

/**
 * Class Users Controller
 * handles user API
 * sha256
 */

 use m4\m4mvc\config\Database;
 use m4\m4mvc\core\Controller;
 use m4\m4mvc\helper\Session;
 use m4\m4mvc\helper\Redirect;

class User extends Controller
{

  public function __construct()
  {
    $this->model = $this->getModel("User");
  }

  public function login()
  {
    if (Session::get('user_id')) Redirect::to('/');
    if (!$_POST || !$_POST['loginEmail'] || !$_POST['loginPassword']) return;

    if (!$user = $this->model->getByEmail($_POST['loginEmail'])) {
        Session::setFlash("User not found.", "danger");
        return;
    }

    if (password_verify($_POST['loginPassword'], $user['password'])) {
        Session::set('user_id', $user['id']);
        Session::setFlash("You are logged in.", "success");
        Redirect::to('/admin');
    } else {
        Session::setFlash("Credentials do not match.", "warning");
    }


  }

    public function logout()
    {
      Session::destroy();
      Redirect::to("/user/login");
    }

    public function profile()
    {
      
    }


}
