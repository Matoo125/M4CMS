<?php
namespace m4\m4cms\controllers\admin;

use m4\m4cms\core\Controller;
use m4\m4mvc\helper\Request;
use m4\m4mvc\helper\Response;
use m4\m4mvc\helper\Redirect;

class Settings extends Controller
{
  public function __construct () 
  {
    $this->model = $this->getModel('Setting');

  }

  public function index () {
    $this->data['settings'] = $this->model->getAll();
    $this->data['themes'] = array_diff(scandir(WEB . DS . 'themes'), ['.','..']);

    $plugin = $this->getModel('Plugin');
    $this->data['plugins'] = $plugin->getAll() ?: [];
    $this->data['pluginsNew'] = array_diff(
      scandir(WEB . DS . 'plugins'), ['.','..'],
      array_column($this->data['plugins'], 'title')
    );
  }

  public function load () 
  {
    $data = $this->model->getAll();
    foreach ($data as $d) {
      $this->data[$d['name']] = $d;
      unset($this->data[$d['name']]['name']);
    }
    return $data;
  }

  public function update ()
  {
    Request::forceMethod('post');
    $data = Request::select(
      'title', 'description', 'tags', 'online', 'navigation', 'theme'
    );

    foreach ($data as $key => $value) {
      $name = $key;
      $value = $value;

      $this->model->update($name, $value);
    }

    Response::success('Settings were updated');
  }

  public function installPlugin ($plugin)
  {
    $model = $this->getModel('Plugin');
    $model->insert([
      'title' => $plugin,
    ]);

    Redirect::to('/admin/settings');
  }

  public function togglePluginStatus ($id)
  {
    $model = $this->getModel('Plugin');
    $model->toggle($id, 'active');

    Redirect::to('/admin/settings');
  }

  public function uninstallPlugin ($id)
  {
    $model = $this->getModel('Plugin');
    $model->delete($id);

    Redirect::to('/admin/settings');
  }



}
