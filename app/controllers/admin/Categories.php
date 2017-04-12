<?php
namespace app\controllers\admin;

use app\controllers\api\Categories as CategoriesApiController;

class Categories extends CategoriesApiController
{
  public function index()
  {
    $this->data['categories'] = $this->model->getAll();
    $this->data['pages'] = $this->model->getPages();
  }


  public function newAjax()
  {
    $data['title'] = $_POST['title'];
    $data['description'] = $_POST['description'] ?: '';
    $data['page_id'] = $_POST['page_id'];
    $data['image'] = isset($_FILES['image'])? $_FILES['image'] : NULL;

    $data['id'] = $this->model->insert($data);
    $data['created_at'] = date("Y-m-d H:i:s");

    echo json_encode($data);
  }

  public function editAjax($id)
  {
    $dbData = $this->get($id);

    $data = [];

    if ($dbData['title'] != $_POST['title']) {
      $data['title'] = $_POST['title'];
    }
    if ($dbData['description'] != $_POST['description']) {
      $data['description'] = $_POST['description'];
    }
    if ($dbData['page_id'] != $_POST['page_id']) {
      $data['page_id'] = $_POST['page_id'];
    }

  //  echo json_encode($_POST);die;

    // check if file was selected
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
      $data['image'] = $_FILES['image'];
    }

    if ($this->model->update($id, $data)) {
      $data = array_merge($dbData, $data);

      if (isset($data['image']['name'])) {
        $data['image'] = "categories" . DS . $data['image']['name'];
      }

    }

    echo json_encode($data);

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
