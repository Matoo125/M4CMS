<?php

namespace app\core;

//use app\string\Lang;
//use app\string\Url;

use app\helper\Session;
use app\helper\Strings;

/*
 * Abstract Controller core class
 * All controllers extend it
 * This is base controller
 */
abstract class Controller {

    protected $model;
    protected $view;
    protected $data = [];

    /**
     * @param $model String
     * @return object new instance of model
     */
    public function model($model) {
        // prepend namespace
        $model = "app\\model\\" . $model;
        return new $model;
    }

    /*
     *  Calls the view
     *  @param $view String
     *  @return void
     *  all templates are twig files
     *  with .twig extension
     */
    public function view($view) {
      ///////////////// DECLARE TWIG INSTANCE /////////////////
      $loader = new \Twig_Loader_Filesystem(APP . DS . 'view');
      $twig = new \Twig_Environment( $loader, array(
        'debug' => true,
      ) );
      $twig->addExtension(new \Twig_Extension_Debug());

      ///////////////// ADD GLOBALS /////////////////

      // session acess
      $twig->addGlobal("session", $_SESSION);

      ///////////////// Create filters /////////////////

      // pass slugify function
      $slugifilter = new \Twig_Filter('slugifilter', 'slugify');
      $twig->addFilter($slugifilter);

      ///////////////// ADD DATA TO ARRAY /////////////////

      // Session core class acess
      $this->data['sessionclass'] = new Session;

      // pass lang and url arrays
      $this->data['lang'] = Strings::getLang();
      $this->data['url']  = Strings::getUrl();


      ///////////////// RENDER TWIG TEMPLATE /////////////////
      echo $twig->render($view.".twig", $this->data);
    }

}
