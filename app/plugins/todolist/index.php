<?php 

use m4\m4cms\core\Plugin;

Plugin::runInPublic(function () {
 // echo 'testman';
});

Plugin::runInAdmin(function () {
 // 
 // register menu item
  Plugin::addNavItem([
    'icon' => 'settings',
    'title' =>  'To Do List',
    'link'  =>  '/admin'
  ]);

 // register path
 // create widget
});

?>