<?php 

namespace m4\m4cms\core;
use Maiorano\Shortcodes\Manager;
use Maiorano\Shortcodes\Library;
use m4\m4cms\model\Gallery;
class Shortcode
{
  public $list = array();
  public $manager = null;

  public function __construct () {

    //Instantiate a Shortcode Manager
    $this->manager = new Manager\ShortcodeManager;
    //Create your shortcode
    $this->list = new Library\SimpleShortcode(
      'gallery', 
      null, 
      function ($content=null, array $attr=[]){
        global $settings;
        if (!$attr) return '';
        $id = $attr['id'];
        $items = (new Gallery)->get($id)['items'];
        
        ob_start();
        $themeSpecific = WEB.DS.'themes'.DS.$settings['theme'].DS.'shortcode'.DS.'Gallery.php';
        if (file_exists($themeSpecific)) {
          require_once $themeSpecific;
        } else {
          require_once(WEB.DS.'shortcode'.DS.'Gallery.php');
        }
        $buffer = ob_get_contents();
        ob_clean();
        return $buffer;
    });

  }
  public function handle ($content) 
  {
    return $this->manager->register($this->list)
                         ->doShortcode($content); 
  }

}