<?php
namespace m4\m4cms\controllers\admin;

use m4\m4cms\interfaces\Crud;

use m4\m4cms\controllers\api\Posts as Controller;

use m4\m4mvc\helper\Request;
use m4\m4mvc\helper\Response;


class Posts extends Controller implements Crud 
{
  public static $fields = [
    'title', 
    'description', 
    'content', 
    'content_delta',
    'tags', 
    'page_id', 
    'category_id', 
    'is_published', 
    'author_id', 
    'image_id'
  ];

  public function index ()
  {
    $this->list();
  }

  public function save ()
  {
    isset($_POST['id']) ? $this->update() : $this->create();
  }

  public function create ()
  {
    // get pages
    $pages = new Pages;
    $this->data['pages'] = $pages->listBasic();
  }

  public function update ($id = null)
  {
    // get post
    $this->id($id);

    // get pages
    $pages = new Pages;
    $this->data['pages'] = $pages->listBasic();

    // get categories if page selected
    if (!$this->data['post']['page_id']) return;

    $categories = new Categories;
    $this->data['categories'] = $categories->listBasic(
      $this->data['post']['page_id']
    );

  }

  public function createAjax ()
  {
    Request::forceMethod('post');
    Request::required('title');
    $data = Request::select(...self::$fields);

    $id = $this->model->insert($data);
    if ($id) {
      ($this->getModel('Media'))->renameFolder(
        'posts', $_POST['tmp_id'], $id
      );
      $data['id'] = $id;
      $data['content'] = str_replace(
        $_POST['tmp_id'], (string) $id, $data['content']
      );
      $this->model->update($data);
    }
    $id ? 
    Response::success('Post was created ', ['id' => $id]) : 
    Response::error('Post was not created. ');
  }

  public function updateAjax ()
  {
    Request::forceMethod('post');
    Request::required('id');
    $data = Request::select(...self::$fields);
    $data['id'] = $_POST['id'];

    $this->model->update($data) ? 
    Response::success('Post was updated ') : 
    Response::error('Post was not updated. ');
  }

  public function delete () 
  {
    Request::forceMethod('post');
    Request::required('id');
    $id = $_POST['id'];

    $this->model->delete($id) ?
    Response::success('Post was deleted') :
    Response::error('There was error and post could not be deleted');
  }
}
