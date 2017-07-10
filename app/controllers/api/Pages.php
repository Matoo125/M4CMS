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
        $this->data = $this->model->getAll() ?: [];
    }

    public function listBasic ()
    {
        $this->data = $this->model->getAllBasic() ?: [];
    }

    public function id ($id)
    {
        $this->data = $this->model->get($id) ?: [];
    }

}
