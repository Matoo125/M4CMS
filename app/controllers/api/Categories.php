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
        $this->data = $pageId ? $this->model->getForPage($pageId) ?? [] : ($this->model->getAll() ?: []);
    }

    public function id ($id)
    {
      $this->data = $this->model->get($id) ?: [];
    }

    public function listBasic ($pageId)
    {
      $this->data = $this->model->getForPageBasic($pageId) ?: [];
    }

}
