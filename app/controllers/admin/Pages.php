<?php
namespace app\controllers\admin;


/*
 * Pages controller.
 */

use app\core\Controller;
use app\helper\Image;
use app\helper\Redirect;



class Pages extends Controller
{

    public function __construct()
    {
        $this->model = $this->model('Page');
    }

    public function index(){}
    public function list(){}

    public function new(){

      if (!$_POST) return;

      $data['title'] = $_POST['title'];
      $data['description'] = $_POST['description'];
      $data['content'] = $_POST['content'];
      $data['publish'] = isset($_POST['publish']) ? 1 : NULL;
      $data['image'] = isset($_FILES['image'])? $_FILES['image'] : NULL;

      // upload image
      if ($data['image']) {
        Image::upload($data['image'], 'pages');
      }

      // send data to model
      if ($id = $this->model->insert($data)) {
        Redirect::to('/admin/pages/edit/' . $id);
      } else {
        echo 'records has not been inserted';
      }



    }
    public function edit($id)
    {

      // get data for the current post
      $this->data = $this->model->get($id);

      if ($_POST) {
        // update only changed data
        $data = [];

        if ($this->data['title'] != $_POST['title']) {
          $data['title'] = $_POST['title'];
        }

        if ($this->data['description'] != $_POST['description']) {
          $data['description'] = $_POST['description'];
        }

        if ($this->data['content'] != $_POST['content']) {
          $data['content'] = $_POST['content'];
        }

        if ($this->data['is_published'] == 1  AND !isset($_POST['publish']) OR $this->data['is_published'] == 0 AND isset($_POST['publish'] )) {
          $data['is_published'] = isset($_POST['publish']) ? 1 : 0;
        }

        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
          $data['image'] = Image::upload($_FILES['image'], 'pages');
        }
      //  var_dump($data); die;

        if ($this->model->update($id, $data)) {
          $this->data = array_merge($this->data, $data);
          if (isset($this->data['image']['name'])) {
            $this->data['image'] = "pages" . DS . $this->data['image']['name'];
          }
        }
      }



    }
    public function trash(){}

}
