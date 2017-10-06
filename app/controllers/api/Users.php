<?php
namespace m4\m4cms\controllers\api;

 use m4\m4mvc\helper\user\UserController;
 use m4\m4mvc\helper\Request;
 use m4\m4mvc\helper\Response;
 use m4\m4mvc\helper\Redirect;
 use m4\m4mvc\helper\Session;

class Users extends UserController
{
  public function __construct () 
  {
    $this->model = $this->getModel('User');
  }

  public function index () {
    $this->list();
  }

  public function list () 
  {
    $this->data['users'] = $this->model->getAll() ?: [];
  }
  
  public function listBasic () 
  {
      $this->data = $this->model->getAllBasic() ?: [];
  }

  public function id ($id)
  {
    $this->data = $this->model->getById($id);
  }

  public function slug ($slug)
  { // model method not written
    $this->data = $this->model->getBySlug(null, $slug);
  }

  public function login ()
  {
    Request::forceMethod('post');
		Request::required('email', 'password');

		$user = $this->model->getByEmail($_POST['email'], 'id, email, username, password ');

		if (!$user) {
		    Response::error('User does not exists');
		}

		if (!password_verify($_POST['password'], $user['password'])) {
			Response::error('Credentials do not match');
		} 

    Session::set('user_id', $user['id']);
    Session::set('user_name', $user['username']);

    Response::success('you are logged in');
  }

  public function logout ()
  {
		Session::destroy();
		Redirect::to('/admin/users/login');
  }
}
