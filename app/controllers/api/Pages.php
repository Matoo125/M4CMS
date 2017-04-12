<?php
namespace app\controllers\api;

use app\core\Controller;

class Pages extends Controller
{

    public function __construct()
    {
        $this->model = $this->model('Page');
    }




}
