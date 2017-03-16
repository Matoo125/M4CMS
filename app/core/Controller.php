<?php

namespace app\core;

class Controller {

    protected $model;
    protected $view;
    protected $data = [];

    /**
     * @param $model String
     * @return mixed new instance of model
     */
    public function model($model) {
        require_once APP . DS . 'model' . DS . $model . '.php';
        return new $model;
    }

    public function view($view) {
      $loader = new \Twig_Loader_Filesystem(APP . DS . 'view');
      $twig = new \Twig_Environment( $loader, array(
        'debug' => true,
      ) );
      $twig->addExtension(new \Twig_Extension_Debug());
      // session acess
      $twig->addGlobal("session", $_SESSION);

      // pass slugify function
      $slugifilter = new \Twig_Filter('slugifilter', 'slugify');
      $twig->addFilter($slugifilter);

      // Session core class acess
      $this->data['sessionclass'] = new Session;

      // pass lang and url arrays
      $this->data['lang'] = \app\string\Lang::getAll();
      $this->data['url']  = new \app\string\Url;

      // render twig template
      echo $twig->render($view.".twig", $this->data);
    }

}
