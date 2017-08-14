<?php
use m4\m4mvc\core\App;
use m4\m4mvc\core\Module;
use m4\m4mvc\helper\Response;

require_once 'app/config/bootstrap.php';


$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();


Module::register(['web', 'admin']);


$app = new App;
$app->settings['namespace'] = 'm4\m4cms';
$app->useTwig();

$app->paths = [
	'controllers'	=> 'controllers',
  'app'         =>  'app',
	'theme'			  => [
    'web'  =>  WEB . DS . 'themes' . DS . 'default' ,
    'admin'   =>  APP . DS . 'admin' 
  ]
];


Response::$errorCode = 200;

$config = parse_ini_file(APP . DS . 'config' . DS . 'App.ini');

$app->db([
	'DB_HOST'		=>	$config['DB_HOST'],
	'DB_PASSWORD'	=>	$config['DB_PASS'],
	'DB_NAME'		=>	$config['DB_NAME'],
	'DB_USER'		=>	$config['DB_USER']
]);
$app->run();