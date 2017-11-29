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

    public function update ($id = null)
    {
      $this->id($id);
    }

    public function createAjax ()
    {
      Request::forceMethod('post');
      Request::required('title', 'is_published');
      $data = Request::select('title', 'description', 'content', 'content_delta', 'is_published', 'image_id');

      $id = $this->model->insert($data); 
      if ($id) {
        ($this->getModel('Media'))->renameFolder(
          'pages', $_POST['tmp_id'], $id
        );
        $data['id'] = $id;
        $data['content'] = str_replace(
          $_POST['tmp_id'], (string) $id, $data['content']
        );
        $this->model->update($data);
      }
      $id ? 
      Response::success('Page was created ', ['id' => $id]) : 
      Response::error('Page was not created. ');
    }

    public function updateAjax()
    {
      Request::forceMethod('post');
      Request::required('id');
      $data = Request::select('id', 'description', 'title', 'content', 'content_delta', 'is_published', 'image_id');

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
