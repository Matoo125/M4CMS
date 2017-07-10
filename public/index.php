<?php
use m4\m4mvc\core\App;

require_once '../app/config/bootstrap.php';

header('Access-Control-Allow-Origin: http://localhost:3000', false); 
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: Content-Type');

$app = new App;
$app->settings['namespace'] = 'm4\m4cms';
$app->useTwig();

$app->paths = [
	'controllers'	=>	APP . DS . 'controllers',
	'views'			=>	APP . DS . 'view'
];

$app->response = 'json';

$config = parse_ini_file(APP . DS . 'config' . DS . 'App.ini');

$app->db([
	'DB_HOST'		=>	$config['DB_HOST'],
	'DB_PASSWORD'	=>	$config['DB_PASS'],
	'DB_NAME'		=>	$config['DB_NAME'],
	'DB_USER'		=>	$config['DB_USER']
]);
$app->run();