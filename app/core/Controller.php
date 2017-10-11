<?php 

namespace m4\m4cms\core;

use m4\m4mvc\core\Controller as BaseController;

class Controller extends BaseController
{
  public function renderTwig ($view = null)
  {
    $this->data['navItems'] = Plugin::getNavItems();


    parent::renderTwig($view);
  }
}