<?php
namespace app\controllers\api;

use app\core\Controller;

class Index extends Controller
{

    public function __construct()
    {
        $this->model = $this->model('Index');
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
