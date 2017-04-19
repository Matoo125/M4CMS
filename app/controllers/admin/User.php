<?php
namespace app\controllers\admin;

use app\controllers\api\User as UserApi;

class User extends UserApi
{
  public function index()
  {
    $this->data['users'] = $this->model->getAll();
  }

  public function getAuthorsAjax()
  {
    echo json_encode( $this->model->getAuthors() );
  }


}
