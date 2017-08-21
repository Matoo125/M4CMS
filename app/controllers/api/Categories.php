<?php
namespace m4\m4cms\controllers\api;

use m4\m4mvc\core\Controller;

class Categories extends Controller
{

    public function __construct()
    {
        $this->model = $this->getModel('Category');
    }

    public function index () {}

    public function list ($pageId = null)
    {
        return $this->data['categories'] = $pageId ? $this->model->getForPage($pageId) ?? [] : ($this->model->getAll() ?: []);
    }

    public function id ($id)
    {
      $this->data['category'] = $this->model->get($id) ?: [];
    }

    public function slug ($slug) 
    {
        $this->data['category'] = $this->model->get(null, $slug) ?: [];
    }

    public function listBasic ($pageId)
    {
      return $this->data['categories'] = $this->model->getForPageBasic($pageId) ?: [];
    }

}
