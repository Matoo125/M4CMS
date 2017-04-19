<?php
namespace app\controllers\admin;

use app\controllers\api\Posts as PostsApi;
use app\helper\Redirect;

class Posts extends PostsApi
{
  public function index()
  {
    $this->data['posts'] = $this->model->getAll();
  }

  public function edit($id)
  {
    $this->data['post'] = $this->model->get($id);

  //  var_dump($this->data['page']);
  }

  public function editAjax()
  {
    if (!$_POST) return false;
    if (count($_POST) < 2) {
       echo 'error';
    } else {
      $id = $_POST['id'];
      array_shift($_POST);
      if ($this->model->update($id, $_POST)) {
        echo 'success';
      }
    }

  }

  public function changeImageAjax()
  {
    $this->model->update($_POST['id'], $_FILES);
    print_r($_FILES['image']['name']);
  }

  public function changeCategoryAjax()
  {
    $this->model->update($_POST);
  }


  public function new()
  {
    $this->data['pages'] = $this->model->getPages();

    if (!$_POST) return;

    $data['title'] = $_POST['title'];
    $data['description'] = $_POST['description'];
    $data['content'] = $_POST['content'];
    $data['tags'] = $_POST['tags'];
    $data['category_id'] = $_POST['category_id'];
    $data['publish'] = isset($_POST['publish']) ? 1 : 0;
    $data['image'] = isset($_FILES['image'])? $_FILES['image'] : NULL;

    // send data to model
    if ($id = $this->model->insert($data)) {
      Redirect::to('/admin/posts/edit/' . $id);
    } else {
      echo 'records has not been inserted';
    }


  }

  public function getCategoriesAjax ()
  {
    echo json_encode( $this->model->getCategories($_POST['page_id']) );
  }

  public function getPagesAjax ()
  {
    echo json_encode( $this->model->getPages() );
  }


}
