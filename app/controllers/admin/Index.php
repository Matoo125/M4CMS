<?php
namespace app\controllers\admin;

use app\controllers\api\Index as IndexApiController;


class Index extends IndexApiController
{
  public function index($page = null, $post = null)
  {
    $this->data['numberOfUsers'] = $this->model->numberOfUsers()['count(*)'];
    $this->data['numberOfPages'] = $this->model->numberOfPages()['count(*)'];
    $this->data['numberOfPosts'] = $this->model->numberOfPosts()['count(*)'];
    $this->data['numberOfCategories'] = $this->model->numberOfCategories()['count(*)'];

  }
}
