<?php
use m4\m4mvc\core\App;
use m4\m4mvc\core\Module;
use m4\m4mvc\core\Model;
use m4\m4mvc\helper\Response;
use m4\m4mvc\helper\Redirect;
use m4\m4cms\model\Setting;
use m4\m4cms\core\Plugin;

require_once 'app/config/bootstrap.php';

$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->pushHandler(new \Whoops\Handler\JsonResponseHandler);
$whoops->register();

Module::register([
  'web' => [
    'render'  =>  'view',
    'folder'  =>  '../public/themes/default'
  ], 
  'admin' => [
    'render'  =>  'view',
    'folder'  =>  'admin',
    'beforeStart' =>  function () {
      if (!isset($_SESSION['user_id'])) {
        if ($_GET['url'] == 'admin/users/login') return;
        Redirect::to('/admin/users/login');
      }
    }
  ], 
  'api' => [ 'render' =>  'json' ]
]);

$app = new App;
$app->settings['namespace'] = 'm4\m4cms';
$app->settings['viewExtension'] = 'twig';
$app->settings['renderFunction'] = 'renderTwig';

$area = 'public';

if (isset($_GET['url'])) {
  if (substr($_GET['url'], 0, 5) === 'admin') {
    $area = 'admin';
  }
}

Response::$errorCode = 200;

$config = parse_ini_file(APP . DS . 'config' . DS . 'App.ini');

$app->db([
	'DB_HOST'		=>	$config['DB_HOST'],
	'DB_PASSWORD'	=>	$config['DB_PASS'],
	'DB_NAME'		=>	$config['DB_NAME'],
	'DB_USER'		=>	$config['DB_USER']
]);

// get settings from database
$settings = new Setting;
$settings = $settings->getValues();

$plugins = explode(';', $settings['plugins']);

foreach ($plugins as $plugin) {
  include_once(APP . DS . 'plugins' . DS . $plugin . DS . 'index.php');
}

if ($area == 'admin') {
  Plugin::runAdminNow();
} else {
  Plugin::runPublicNow();
}


$app->run();