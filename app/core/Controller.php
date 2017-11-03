<?php 

namespace m4\m4cms\core;

use m4\m4mvc\core\Controller as BaseController;

class Controller extends BaseController
{

  public function renderTwig($view = null)
	{
    $this->data['navItems'] = Plugin::getNavItems();
    
		$view = str_replace($this->pathToTheme, '', $view);

		if ($this->view) {
			$view = $this->view;
		}
		// Declare twig instance
		$loader = new \Twig_Loader_Filesystem($this->pathToTheme);
		$twig = new \Twig_Environment(
			$loader, 
			array(
		  	'debug' => true,
			)
		);
		$twig->addExtension(new \Twig_Extension_Debug());
		// Add Globals
		$twig->addGlobal("session", $_SESSION);
		// Create filters 
		$slugifilter = new \Twig_Filter(
			'slugify', 
			'\\m4\\m4mvc\\helper\\Str::slugify'
		);
		$twig->addFilter($slugifilter);
		$twig->addExtension(new \Twig_Extension_StringLoader());
		
		// Add data to array
		$this->data['sessionclass'] = new \m4\m4mvc\helper\Session;
		
		// Pass lang and url arrays
		$this->data['lang'] = \m4\m4mvc\helper\Str::getLang();
		$this->data['url']  = \m4\m4mvc\helper\Str::getUrl();

		echo $twig->render($view, $this->data);
	}
}