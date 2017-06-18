<?php
namespace m4\m4cms\controllers\api;

 use m4\m4mvc\helper\user\UserController;

class Users extends UserController
{
  public function __construct () 
  {
    $this->model = $this->getModel('User');
  }
  
  public function listBasic () 
  {
      $this->data = $this->model->getAllBasic() ?: [];
  }
}
