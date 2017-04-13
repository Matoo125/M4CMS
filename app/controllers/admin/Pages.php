<?php
namespace app\controllers\admin;


/*
 * Pages controller.
 */

use app\helper\Image;
use app\helper\Redirect;

use app\controllers\api\Pages as PagesApi;


class Pages extends PagesApi
{

    public function index(){
      $this->list();
    }

    public function list(){
      $this->data['pages'] = $this->model->getAll();

    }

    public function new(){

      if (!$_POST) return;

      $data['title'] = $_POST['title'];
      $data['description'] = $_POST['description'];
      $data['content'] = $_POST['content'];
      $data['publish'] = isset($_POST['publish']) ? 1 : 0;
      $data['image'] = isset($_FILES['image'])? $_FILES['image'] : NULL;

      // send data to model
      if ($id = $this->model->insert($data)) {
        Redirect::to('/admin/pages/edit/' . $id);
      } else {
        echo 'records has not been inserted';
      }

    }

    public function editAjax()
    {

    }

    public function edit($id)
    {

      // get data for the current post
      $this->data = $this->model->get($id);

      $this->data['categories'] = $this->model->getCategories($id);


      if ($_POST) {
        // update only changed data
        $data = [];


        if ($this->data['title'] != $_POST['title']) {
          $data['title'] = $_POST['title'];
        }

        // check for description change
        if ($this->data['description'] != $_POST['description']) {
          $data['description'] = $_POST['description'];
        }

        // check for content change
        if ($this->data['content'] != $_POST['content']) {
          $data['content'] = $_POST['content'];
        }

        // check for is_published change
        if ($this->data['is_published'] == 1  AND !isset($_POST['publish']) OR $this->data['is_published'] == 0 AND isset($_POST['publish'] )) {
          $data['is_published'] = isset($_POST['publish']) ? 1 : 0;
        }

        // check if file was selected
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
          $data['image'] = $_FILES['image'];
        }

        // do the update
        if ($this->model->update($id, $data)) {
          // inject updated data to view
          $this->data = array_merge($this->data, $data);

          // inject new image to view if necessary
          if (isset($this->data['image']['name'])) {
            $this->data['image'] = "pages" . DS . $this->data['image']['name'];
          }

        }

      }



    }
    public function trash(){}

}
