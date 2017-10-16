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
    'link'  =>  '/admin/todolist'
  ]);

 // register paths
 Plugin::addPath('todolist', 'm4\\m4cms\\Plugin\\Todolist\\Controller', 'index');
 Plugin::addPath('todolist/add', 'm4\\m4cms\\Plugin\\Todolist\\Controller', 'add');
 Plugin::addPath('todolist/toggle', 'm4\\m4cms\\Plugin\\Todolist\\Controller', 'toggle');
 // create widget
});

?>