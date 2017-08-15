<?php
namespace m4\m4cms\controllers\api;

use m4\m4mvc\core\Controller;

class Pages extends Controller
{

    public function __construct ()
    {
        $this->model = $this->getModel('Page');
    }

    public function index () {}


    public function list ()
    {
        $this->data['pages'] = $this->model->getAll() ?: [];
    }

    public function listBasic ()
    {
        return $this->data['pages'] = $this->model->getAllBasic() ?: [];
    }

    public function id ($id)
    {
        return $this->data['page'] = $this->model->get($id) ?: [];
    }

    public function slug ($slug)
    {
        return $this->data['page'] = $this->model->get(null, $slug) ?: [];
    }

}
