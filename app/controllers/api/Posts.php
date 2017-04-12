<?php
namespace app\controllers\api;

use app\core\Controller;

class Posts extends Controller
{

    public function __construct()
    {
        $this->model = $this->model('Post');
    }


    public function get()
    {

    }



}
