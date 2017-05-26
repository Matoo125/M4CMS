<?php

session_start();

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(dirname(__DIR__)));
define('APP', dirname(__DIR__) );
define('WEB', ROOT.DS.'public');
define('UPLOADS', WEB .DS.'uploads' );

$config = parse_ini_file(APP.DS.'config'.DS.'App.ini');

// echo '<pre>'; print_r($config);die;

define('DEVELOPMENT', $config['DEVELOPMENT']);


if (DEVELOPMENT) {
	error_reporting(E_ALL); // allow error reporting
	ini_set('display_errors', 1); // display them
}

require_once APP . DS . 'vendor/autoload.php';
