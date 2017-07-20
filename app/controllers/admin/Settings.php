<?php
namespace m4\m4cms\controllers\admin;

use m4\m4mvc\core\Controller;
use m4\m4mvc\helper\Request;
use m4\m4mvc\helper\Response;

class Settings extends Controller
{
  public function __construct () 
  {
    $this->model = $this->getModel('Setting');

  }

  public function index () {}

  public function load () 
  {
    $data = $this->model->getAll();
    foreach ($data as $d) {
      $this->data[$d['name']] = $d;
      unset($this->data[$d['name']]['name']);
    }
    return;
  }

  public function update ()
  {
    Request::forceMethod('post');
    $data = Request::select('title', 'description', 'tags', 'online');

    foreach ($data as $key => $value) {
      $name = $key;
      $value = $value;

      $this->model->update($name, $value);
    }

    Response::success('Settings were updated');
  }




}
