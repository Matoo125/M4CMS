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
