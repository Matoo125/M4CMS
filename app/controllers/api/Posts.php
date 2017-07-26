<?php
namespace m4\m4cms\controllers\api;

use m4\m4mvc\core\Controller;

class Posts extends Controller
{

    public function __construct ()
    {
        $this->model = $this->getModel('Post');
    }

    public function index () {}

    public function list ()
    {
        $this->data = $this->model->getAll() ?: [];
    }

    public function listByCategory ($categoryId) 
    {
        return $this->data = $this->model->getAllByCategory($categoryId) ?: [];
    }

    public function listByPage ($pageId) 
    {
        return $this->data = $this->model->getAllByPage($pageId) ?: [];
    }

    public function listByPageWC ($pageId)
    {
        return $this->data = $this->model->getAllByPageWithoutCategory($pageId) ?: [];
    }

    public function id ($id)
    {
        return $this->data = $this->model->get($id) ?: [];
    }

    public function slug ($slug)
    {
        return $this->data = $this->model->get(null, $slug) ?: [];
    }

}
