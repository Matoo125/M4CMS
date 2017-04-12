<?php
namespace app\controllers\api;

use app\core\Controller;

class Categories extends Controller
{

    public function __construct()
    {
        $this->model = $this->model('Category');
    }




}
