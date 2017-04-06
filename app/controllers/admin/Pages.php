<?php
namespace app\controllers\admin;

use app\core\Controller;
use app\helper\Image;

class Pages extends Controller
{
    public function index(){}
    public function list(){}
    public function new(){

      if (!$_POST) return;

      $title = $_POST['title'];
      $desc = $_POST['description'];
      $content = $_POST['content'];
      $publish = isset($_POST['publish']) ? $_POST['publish'] : NULL;
      $image = isset($_FILES['image'])? $_FILES['image'] : NULL;

      if ($image) {
        var_dump($_FILES);
        echo Image::upload($image, 'pages'); die;
      }

      var_dump($_FILES);
      var_dump($_POST); die;


    }
    public function edit(){}
    public function trash(){}

}
