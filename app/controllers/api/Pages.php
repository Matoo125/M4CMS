<?php
namespace m4\m4cms\controllers\api;

use m4\m4mvc\core\Controller;

class Pages extends Controller
{

    public function __construct()
    {
        $this->model = $this->getModel('Page');
    }




}
