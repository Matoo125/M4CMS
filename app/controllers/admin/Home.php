<?php
namespace m4\m4cms\controllers\admin;

use m4\m4cms\controllers\api\Home as IndexApiController;
use m4\m4mvc\helper\Response;
use m4\m4cms\core\Plugin;

class Home extends IndexApiController
{
  public function index($page = null, $post = null)
  {

    if (Plugin::is_active()) {
      $this->data = Plugin::$data;
      $this->view = 'Plugin/Index.twig';
      return true;
    }

    $this->data['numberOfUsers'] = $this->model->countTable('users');
    $this->data['numberOfPages'] = $this->model->countTable('pages');
    $this->data['numberOfPosts'] = $this->model->countTable('posts');
    $this->data['numberOfCategories'] = $this->model->countTable('categories');

    //if ($page) { echo $page; die; }
   // Response::success('Response from home arrived ...', $this->data);

  }
}
