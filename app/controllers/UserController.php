<?php

/**
 * Class Users Controller
 * login logic
 * insecure, but enough for working purposes
 * before release, please upgrade.
 */

class UserController extends Controller {

    public function index() 
    {

    }

    public function public_index()
    {

    }

    public function public_login() 
    {
        
    }

    public function login()
    {
        if ($_POST) {
            $mEmail = $_POST['loginEmail'];
            $mPass = $_POST['loginPassword'];

            if ($mEmail && $mPass) {

                $user = $this->model('User');
                if (!$user = $user->getByEmail($mEmail)) {
                    Session::setFlash("User not found.", "danger");
                    return;
                }

                $mPass = md5(Config::SALT . $mPass);

                if ($mPass == $user['password']) {
                    Session::set('user_id', $user['id']);
                    Session::setFlash("You are logged in.", "success");
                    redirect('admin');
                } else {
                    Session::setFlash("Credentials do not match.", "warning");
                }
            } else {
                Session::setFlash("No input received", "info");
            }
        }

    }

    public function __destruct()
    {
        $this->view('admin/login');

    }

}