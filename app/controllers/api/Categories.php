<?php
namespace m4\m4cms\controllers\api;

use m4\m4mvc\core\Controller;

class Categories extends Controller
{

    public function __construct()
    {
        $this->model = $this->getModel('Category');
    }

    public function list ()
    {
      $this->data = $this->model->getAll() ?: [];
    }

    public function id ($id)
    {
      $this->data[] = $this->model->get($id);
    }

}
