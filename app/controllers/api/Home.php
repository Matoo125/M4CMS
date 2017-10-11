<?php
namespace m4\m4cms\controllers\api;

use m4\m4cms\core\Controller;

class Home extends Controller
{

    public function __construct()
    {
        $this->model = $this->getModel('Home');

    }

    public function index($page = null, $post = null)
    {
       if ( $page != null ) {
           $this->data['page'] = $page;

           if ( $post != null ) {
               $this->data['post'] = $post;
           }
       }
    }


}
