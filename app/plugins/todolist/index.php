<?php 

use m4\m4cms\core\Plugin;

Plugin::runInPublic(function () {
 // echo 'testman';
});

Plugin::runInAdmin(function () {
 // echo 'testman admin';
 // 
 // register menu item
 // register path
 // create widget
});

?>