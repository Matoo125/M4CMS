<?php
namespace m4\m4cms\controllers\api;

use m4\m4mvc\core\Controller;

class Categories extends Controller
{

    public function __construct()
    {
        $this->model = $this->getModel('Category');
    }

    public function list ($pageId = null)
    {
        $pageId ? $this->forPage($pageId) : ($this->data = $this->model->getAll() ?: []);
    }

    public function id ($id)
    {
      $this->data[] = $this->model->get($id);
    }

    public function forPage ($pageId)
    {
      $this->data = $this->model->getForPage($pageId) ?: [];
    }

}
