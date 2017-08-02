<?php 

require_once('../../app/vendor/autoload.php');

use m4\m4mvc\helper\Request;

Request::handle();

Request::forceMethod('POST');

Request::required('database-host', 'database-name', 'database-username', 'user-username', 'user-password', 'user-password2', 'user-email', 'website-title', 'website-description');

$db = [
  'host'      =>  $_POST['database-host'],
  'name'      =>  $_POST['database-name'],
  'username'  =>  $_POST['database-username'],
  'password'  =>  $_POST['database-password']
];

$user = [
  'username'  =>  $_POST['user-username'],
  'email'     =>  $_POST['user-email'],
  'password'  =>  $_POST['user-password'],
  'password2' =>  $_POST['user-password2']
];

$website = [
  'title'       =>  $_POST['website-title'],
  'description' =>  $_POST['website-description']
];


if ($user['password'] !== $user['password2']){
  return json_encode([
    'status'  =>  'ERROR',
    'message' =>  'Passwords do not match'
  ]);
}

// create database
try {
  $conn = new PDO(
    "mysql:host=" . $db['host'], 
    $db['username'], 
    $db['password']);


  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $sql = "DROP DATABASE IF EXISTS " . $db['name'];
  $conn->exec($sql);

  $sql = "CREATE DATABASE " . $db['name'];
  $conn->exec($sql);

  echo 'Database created successfully';
} catch (PDOException $e) {
  echo $sql . '<br>' . $e->getMessage();
}

// select created database
$conn->exec('USE ' . $db['name']);

// insert tables
$sql = file_get_contents('../../db/tables.sql');
$conn->exec($sql);

// insert settings
$sql = "
INSERT INTO `settings` (`name`, `value`) VALUES ('title', '" . $website['title'] . "');
INSERT INTO `settings` (`name`, `value`) VALUES ('description', '" . $website['description'] . "');
INSERT INTO `settings` (`name`) VALUES ('tags');
INSERT INTO `settings` (`name`, `value`) VALUES ('online', 1);
INSERT INTO `settings` (`name`) VALUES ('navigation');
";
$conn->exec($sql);

// insert user
require_once('../../app/vendor/m4/m4mvc/src/helper/Str.php');
$sql  = "INSERT INTO `users` (`username`, `slug`, `password`, `email`, `role`) ";
$sql .= "VALUES ('";
$sql .= $user['username'] . "', '";
$sql .= \m4\m4mvc\helper\Str::slugify($user['username']) ."', '";
$sql .= password_hash($user['password'], PASSWORD_DEFAULT) . "', '";
$sql .= $user['email'] . "', '";
$sql .= "4')";

$conn->exec($sql);

$conn = null;

header("Location: completed.php");
// create tables

// insert user

// insert settings