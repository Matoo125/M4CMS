<?php

/**
 * Class Users Controller
 * login logic
 * insecure, but enough for working purposes
 * before release, please upgrade.
 */

class UserController extends Controller {

  public function __construct()
  {
    $this->model = $this->model("User");
  }

    public function index(){}

    public function public_index(){}

    public function public_login() {}

    public function login()
    {
        if ($_POST) {
            $mEmail = $_POST['loginEmail'];
            $mPass = $_POST['loginPassword'];

            if ($mEmail && $mPass) {

                if (!$user = $this->model->getByEmail($mEmail)) {
                    Session::setFlash("User not found.", "danger");
                    return;
                } else {
                //  echo 'false'; die;
                }

                $mPass = md5(Config::SALT . $mPass);

                if ($mPass == $user['password']) {
                    Session::set('user_id', $user['id']);
                    Session::setFlash("You are logged in.", "success");
                    redirect('/admin');
                } else {
                    Session::setFlash("Credentials do not match.", "warning");
                }
            } else {
                Session::setFlash("No input received", "info");
            }
        }

    }

    public function logout()
    {
      Session::destroy();
      redirect(toURL("LOGIN"));
    }

    public function public_logout(){}


}
