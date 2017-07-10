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
        $this->data = $this->model->getAllByCategory($categoryId) ?: [];
    }

    public function listByPage ($pageId) 
    {
        $this->data = $this->model->getAllByPage($pageId) ?: [];
    }

    public function id ($id)
    {
        $this->data = $this->model->get($id) ?: [];
    }

}
