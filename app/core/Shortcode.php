<?php 

namespace m4\m4cms\core;
use Maiorano\Shortcodes\Manager;
use Maiorano\Shortcodes\Library;
class Shortcode
{
  public $list = array();
  public $manager = null;

  public function __construct () {

    //Instantiate a Shortcode Manager
    $this->manager = new Manager\ShortcodeManager;
    //Create your shortcode
    $this->list = new Library\SimpleShortcode('shortcode', null, function(){
        return 'hello this is gallery';
    });

  }
  public function handle ($content) 
  {
    return $this->manager->register($this->list)->doShortcode($content); 
  }

}