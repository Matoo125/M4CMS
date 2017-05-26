<?php

namespace app\core;

use app\helper\Redirect;
use app\helper\Session;


/**
 * App class is the application headquarters.
 * This is calling controllers and also view
 * It is also router of the application.
 */
class App
{
    // Controller to be called.
    // Default Index
    // Later controller object
    protected $controller = 'Index';
    // Method to be called
    // default index
    protected $method = 'index';
    // Module to be called
    // default web
    protected $module = 'web';
    // Params to be passed to controller
    // default empty array
    protected $params = [];


    /**
     * App constructor.
     * Everything important is happening here
     */
    public function __construct()
    {
        // create array from and clean the url
        $url = $this->parseURL();

        // set the module
        $url = $this->setModule($url);

        // set controller if exists
        // if not we'll call default controller
        // which is already set
        if ($url && file_exists(APP . DS . 'controllers' . DS . $this->module . DS . ucfirst($url[0] ) . '.php')) {
            $this->controller = ucfirst($url[0]); // set controller from URL
            array_shift($url);
        }

        // prepend namespace
        // create new instance of the controller
        $controller = "app\controllers\\" . $this->module . "\\" . $this->controller;
        $this->controller = new $controller();

        // set method
        // if possible and necessary
        // else we'll use default method: index
        if ( isset( $url[0] ) && method_exists( $this->controller, $url[0] ) ) {
            $this->method = $url[0];
            array_shift($url);
        }

        // set params if possible
        // all the remaining arguments
        // from url or empty []
        $this->params = $url ? $url : [];

        if ( method_exists( $this->controller, $this->method) ) {

          // call controller's method with parameters
          call_user_func_array([$this->controller, $this->method], $this->params);

          // call view
          // module/controller/method
          $view = $this->module . DS . lcfirst(substr(strrchr(get_class($this->controller), '\\'), 1)) . DS . $this->method;
          if (file_exists(APP . DS . 'view' . DS . $view . '.twig')) {
            call_user_func_array([$this->controller, 'view'], [$view]);
          }

        }


    }

    /**
    * setModule function
    * takes 1 argument $url array
    * return $url
    */
    public function setModule($url)
    {
        $modules = array_diff(scandir(APP.DS.'controllers'), ['.', '..', 'web']);
        if (in_array($url[0], $modules)) {
            $this->module = $modules[array_search($url[0], $modules)];
            array_shift($url);
        }
        return $url;
    }

    /*
    * parseURL no arguments
    * returns url array
    * or null
    */
    public function parseURL()
    {
        if (isset($_GET['url'])) {
            return $url = explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }

        return null;
    }


    public function __destruct()
    {
      /*
      $time =  microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];
      echo "<script>alert(".$time.")</script>";
      */
    }

}
