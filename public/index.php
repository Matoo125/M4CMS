<?php
use m4\m4mvc\core\App;
use m4\m4mvc\helper\Response;


header("Connection: Keep-alive");
header('Access-Control-Allow-Origin: http://localhost:3000'); 
//header('Access-Control-Allow-Origin: http://localhost:8080', false); 
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
require_once '../app/config/bootstrap.php';
//require_once 'api/config/bootstrap.php';



$app = new App;
$app->settings['namespace'] = 'm4\m4cms';
$app->useTwig();

$app->paths = [
	'controllers'	=>	APP . DS . 'controllers',
	'views'			=>	APP . DS . 'view'
];

Response::$errorCode = 200;

if (isset($_GET['url']) && substr($_GET['url'], 0, 5)  === 'admin') {
  $app->response = 'json';
}

$app->module = 'web';

$config = parse_ini_file(APP . DS . 'config' . DS . 'App.ini');

$app->db([
	'DB_HOST'		=>	$config['DB_HOST'],
	'DB_PASSWORD'	=>	$config['DB_PASS'],
	'DB_NAME'		=>	$config['DB_NAME'],
	'DB_USER'		=>	$config['DB_USER']
]);
$app->run();