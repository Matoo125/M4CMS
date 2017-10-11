<?php
namespace m4\m4cms\controllers\api;

use m4\m4cms\core\Controller;

class Posts extends Controller
{

    public function __construct ()
    {
        $this->model = $this->getModel('Post');
    }

    public function index () {}

    public function list ()
    {
        $this->data['posts'] = $this->model->getAll() ?: [];
    }

    public function listByCategory ($categoryId) 
    {
        return $this->data['posts'] = $this->model->getAllByCategory($categoryId) ?: [];
    }

    public function listByPage ($pageId) 
    {
        return $this->data['posts'] = $this->model->getAllByPage($pageId) ?: [];
    }

    public function listByPageWC ($pageId)
    {
        return $this->data['posts'] = $this->model->getAllByPageWithoutCategory($pageId) ?: [];
    }

    public function id ($id)
    {
        return $this->data['post'] = $this->model->get($id) ?: [];
    }

    public function slug ($slug)
    {
        return $this->data['post'] = $this->model->get(null, $slug) ?: [];
    }

}
