<?php
namespace m4\m4cms\controllers\api;

 use m4\m4mvc\helper\user\UserController;

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
}
