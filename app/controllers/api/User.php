<?php
namespace app\controllers\api;

/**
 * Class Users Controller
 * login logic
 * insecure, but enough for working purposes
 * before release, please upgrade.
 */

 use app\config\Database;
 use app\core\Controller;
 use app\core\Session;
 use app\helpers\Redirect;

class User extends Controller
{

  public function __construct()
  {
    $this->model = $this->model("User");
  }

  public function login()
    {
      if (Session::get('user_id')) Redirect::to('/');
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

                $mPass = md5(Database::SALT . $mPass);

                if ($mPass == $user['password']) {
                    Session::set('user_id', $user['id']);
                    Session::setFlash("You are logged in.", "success");
                    Redirect::to('/admin');
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
      Redirect::to("/user/login");
      //Helper\redirect(toURL("LOGIN"));
    }


}
