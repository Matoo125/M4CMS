<?php
namespace m4\m4cms\controllers\admin;

use m4\m4cms\controllers\api\Categories as CategoriesApiController;
use m4\m4mvc\helper\Response;
use m4\m4mvc\helper\Request;

class Categories extends CategoriesApiController
{
  public function create () 
  {
    Request::forceMethod('post');
    Request::required('title', 'description', 'page_id');
    $data = Request::select('title', 'description', 'page_id');

    $this->model->insert($data) ? Response::success('Category has been created. ') : Response::error('Category has not created. ');
  }

  public function update ()
  {
    Request::forceMethod('post');
    Request::required('id');
    Request::select('id', 'description', 'title');

    $this->model->update($data) ? Response::success('Category has been updated. ') : Response::error('Category has not been updated. ');
  }

  public function get($id)
  {
    return $this->model->getById($id);
  }

  public function getAjax($id)
  {
    echo json_encode($this->get($id));
  }




}
