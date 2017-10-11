<?php
namespace m4\m4cms\controllers\api;

use m4\m4cms\core\Controller;

class Media extends Controller
{

    public function __construct()
    {
        $this->model = $this->getModel('Media');
    }

    public function index () {
        $this->data['media'] = $this->list();
    }

    public function list ($pageId = null)
    {
        return $this->data['media'] = $pageId ? $this->model->getForPage($pageId) ?? [] : ($this->model->getAll() ?: []);
    }

    public function id ($id)
    {
      $this->data = $this->model->get($id) ?: [];
    }


}
