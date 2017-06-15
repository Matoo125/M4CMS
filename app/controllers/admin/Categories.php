<?php
namespace m4\m4cms\controllers\admin;

use m4\m4cms\controllers\api\Categories as Controller;
use m4\m4mvc\helper\Response;
use m4\m4mvc\helper\Request;
use m4\m4cms\interfaces\Crud;

class Categories extends Controller implements Crud 
{

  public function save () 
  {
	  isset($_POST['id']) ? $this->update() : $this->create(); 
  }

  public function create () 
  {
    Request::forceMethod('post');
    Request::required('title', 'description', 'page_id');
    $data = Request::select('title', 'description', 'page_id');

    $this->model->insert($data) ? 
    Response::success('Category has been created. ') : 
    Response::error('Category has not created. ');
  }

  public function update ()
  {
    Request::forceMethod('post');
    Request::required('id');
    $data = Request::select('id', 'description', 'title', 'page_id');

    $this->model->update($data) ? 
    Response::success('Category has been updated. ') : 
    Response::error('Category has not been updated. ');
  }

  public function delete ()
  {
    
  }

}
