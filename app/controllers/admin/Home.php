<?php
namespace m4\m4cms\controllers\admin;

use m4\m4cms\controllers\api\Home as IndexApiController;
use m4\m4mvc\helper\Response;

class Home extends IndexApiController
{
  public function index($page = null, $post = null)
  {
    $this->data['numberOfUsers'] = $this->model->numberOfUsers()['count(*)'];
    $this->data['numberOfPages'] = $this->model->numberOfPages()['count(*)'];
    $this->data['numberOfPosts'] = $this->model->numberOfPosts()['count(*)'];
    $this->data['numberOfCategories'] = $this->model->numberOfCategories()['count(*)'];

    Response::success('Response from home arrived', $this->data);

  }
}
