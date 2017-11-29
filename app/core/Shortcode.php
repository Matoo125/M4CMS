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
    $this->list = new Library\SimpleShortcode('gallery', null, function(){
        $items = array_diff(scandir(UPLOADS.DS.'pages'.DS.'29'), ['.', '..']);
        $html = '<div class="row text-center text-lg-left">';
        foreach ($items as $item) {
          $html .= '<div class="col-lg-3 col-md-4 col-xs-6">';
          $html .= '<a href="#" class="d-block mb-4 h-100">';
          $html .= '<img class="img-fluid img-thumbnail" src="/public/uploads/pages/29/'.$item.'">';
          $html .= '</a></div>';
        }
        $html .= '</div>';
        return $html;
    });

  }
  public function handle ($content) 
  {
    return $this->manager->register($this->list)
                         ->doShortcode($content); 
  }

}