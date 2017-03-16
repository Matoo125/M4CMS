<?php

error_reporting(E_ALL); // allow error reporting
ini_set('display_errors', 1); // display them

session_start();

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(dirname(__DIR__)));
define('APP', dirname(__DIR__) );

require_once APP . DS . 'string/url.php';
require_once APP . DS . 'string/en.php';
require_once APP . DS . 'core/App.php';
require_once APP . DS . 'core/Controller.php';
require_once APP . DS . 'core/Model.php';
require_once 'database.php';
require_once APP . DS . 'core/Session.php';
require_once APP . DS . 'core/Helper.php';



require_once APP . DS . 'vendor/autoload.php';
