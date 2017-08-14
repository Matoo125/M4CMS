<?php
namespace m4\m4cms\controllers\admin;


/*
 * Pages controller.
 */

use m4\m4cms\interfaces\Crud;
use m4\m4mvc\helper\Request;
use m4\m4mvc\helper\Response;

use m4\m4cms\controllers\api\Pages as PagesApi;


class Pages extends PagesApi implements Crud
{

    public function index ()
    {
      //echo 'welcome to pages';die;     
      $this->list();
    }

    public function save ()
    {
      isset($_POST['id']) ? $this->update() : $this->create();
    }

    public function create ()
    {
      
    }

    public function createAjax ()
    {
      Request::forceMethod('post');
      Request::required('title', 'is_published');
      $data = Request::select('title', 'description', 'content', 'is_published', 'image_id');

      $id = $this->model->insert($data); 
      $id ? 
      Response::success('Page was created ', ['id' => $id]) : 
      Response::error('Page was not created. ');
    }

    public function update()
    {
      Request::forceMethod('post');
      Request::required('id');
      $data = Request::select('id', 'description', 'title', 'content', 'is_published', 'image_id');

      $this->model->update($data) ? 
      Response::success('Page was updated ') : 
      Response::error('Page was not updated. ');

    }

    public function delete(){
      Request::forceMethod('post');
      Request::required('id');
      $id = $_POST['id'];

      $this->model->delete($id) ?
      Response::success('Page was deleted') :
      Response::error('There was error and page could not be deleted');

    }

}
