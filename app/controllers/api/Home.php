<?php
namespace m4\m4cms\controllers\api;

use m4\m4mvc\core\Controller;

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

    // used for uploading images
    // with ajax or any POST request
    public function uploadImage()
    {
      $data = array(
        'message' => 'uploadSuccess',
        'file'    => '$file',
    );
      echo json_encode($data);
    }


}
