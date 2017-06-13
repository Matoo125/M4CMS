<?php
namespace m4\m4cms\controllers\api;

use m4\m4mvc\core\Controller;

class Posts extends Controller
{

    public function __construct()
    {
        $this->model = $this->getModel('Post');
    }


    public function get()
    {

    }



}
