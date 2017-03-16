<?php

class App
{
    protected $controller = 'IndexController';
    protected $method = 'index';
    protected $method_prefix = 'public_';
    protected $params = [];
    protected $view;

    /**
     * App constructor.
     */
    public function __construct()
    {
        // create array from and clean the url
        $url = $this->parseURL();

        // set admin prefix if exists
         if ($url[0] == 'admin'){
            $this->method_prefix = 'admin_';
            array_shift($url);
            if (! Session::get('user_id')) redirect(toURL("LOGIN"));
          }

        // set api prefix if exists
        if (isset($url[0]) && $url[0] == 'api') {
            $this->method_prefix = "api_";
            array_shift($url);
        }

        // set first folder for view
        $this->view = substr($this->method_prefix, 0, -1) . "/";

        // set controller if exists
        if ($url && file_exists(APP . DS . 'controllers' . DS . ucfirst($url[0] . 'Controller') . '.php')) {
            $this->controller = ucfirst($url[0]) . "Controller"; // set controller from URL
            array_shift($url);
        }

        // set second folder for view
        $this->view .= lcfirst(substr($this->controller, 0, -10)) . "/";

        // require controller file
        require_once APP . DS . 'controllers' . DS . $this->controller . '.php';

        // create new instance of the controller
        $this->controller = new $this->controller();

        if ( isset( $url[0] ) && method_exists( $this->controller, $this->method_prefix . $url[0] ) ) {
            $this->method = $url[0];
            array_shift($url);
        }
        // set file for view
        $this->view .= $this->method;

        // set params if possible
        $this->params = $url ? $url : [];

        // call method in controller and pass parameters
        if ( method_exists( $this->controller, $this->method_prefix . $this->method) ) {
          // call method
          call_user_func_array([$this->controller, $this->method], $this->params);
          // call view
          call_user_func_array([$this->controller, 'view'], [$this->view]);
        }


    }

    public function parseURL()
    {
        if (isset($_GET['url'])) {
            return $url = explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }

        return null;
    }

}
