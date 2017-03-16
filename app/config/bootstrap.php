<?php

error_reporting(E_ALL); // allow error reporting
ini_set('display_errors', 1); // display them

session_start();

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(dirname(__DIR__)));
define('APP', dirname(__DIR__) );


require_once APP . DS . 'vendor/autoload.php';
