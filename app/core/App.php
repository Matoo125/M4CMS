<?php 
namespace m4\m4cms\core;

use m4\m4mvc\helper\Request;
use m4\m4mvc\core\Model;
use m4\m4mvc\core\Module;
use m4\m4mvc\core\App as OriginalApp;

class App extends OriginalApp
{

  public function run()
  {
    // handle request
    $url = Request::handle();

    // set module from url
    if (Module::$active AND is_array($url)) { $url = Module::set($url); }

    Module::beforeStart();

    if (!(is_array($url) AND Plugin::matchUrl(implode('/', $url)))) {
      // create instance of controller
      $url = parent::setController($url);
      // call the method
      parent::callMethod($url);
      // before end
      Module::beforeEnd();
    }

  }




}

