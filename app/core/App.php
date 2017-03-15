<?php

class App
{
    protected $controller = 'IndexController';
    protected $method = 'index';
    protected $method_prefix = 'public_';
    protected $params = [];

    /**
     * App constructor.
     */
    public function __construct()
    {
        $url = $this->parseURL();
        
         if ($url[0] == 'admin'){
            $this->method_prefix = 'admin_';
            array_shift($url);
            if (! Session::get('user_id')) redirect(LOGIN_PAGE);
          }

        if (isset($url[0]) && $url[0] == 'api') {
            $this->method_prefix = "api_";
            array_shift($url);
        }

        if ($url && file_exists(APP . DS . 'controllers' . DS . ucfirst($url[0] . 'Controller') . '.php')) {
            $this->controller = ucfirst($url[0]) . "Controller"; // set controller from URL
            array_shift($url);
        }

        require_once APP . DS . 'controllers' . DS . $this->controller . '.php'; // require controller file
        $this->controller = new $this->controller(
            substr($this->method_prefix, 0, -1) . "/" . 
            lcfirst(substr($this->controller, 0, -10)) . "/" . 
            $this->method
        ); // create new instance of controller in member variable controller

        // set method if exists and add prefix
        if (isset($url[0]) && method_exists($this->controller, $this->method_prefix . $url[0])) {
            $this->method = $url[0];
            array_shift($url);
        } 

        // set params from url
        $this->params = $url ? $url : [];
        // call method in controller and pass parameters
        call_user_func_array([$this->controller,$this->method], $this->params);
        call_user_func_array([$this->controller,$this->method_prefix . $this->method], $this->params);
    }

    public function parseURL()
    {
        if (isset($_GET['url'])) {
            return $url = explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }

        return null;
    }

}