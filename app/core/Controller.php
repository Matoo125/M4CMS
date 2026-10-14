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
		$loader = new \Twig\Loader\FilesystemLoader($this->pathToTheme);
		$twig = new \Twig\Environment(
			$loader, 
			array(
		  	'debug' => true,
			)
		);
		$twig->addExtension(new \Twig\Extension\DebugExtension());
		// Add Globals
		$twig->addGlobal("session", $_SESSION);
		// Create filters 
		$slugifilter = new \Twig\TwigFilter(
			'slugify', 
			'\\m4\\m4mvc\\helper\\Str::slugify'
		);
		$twig->addFilter($slugifilter);
		$twig->addExtension(new \Twig\Extension\StringLoaderExtension());
		
		// Add data to array
		$this->data['sessionclass'] = new \m4\m4mvc\helper\Session;
		
		// Pass lang and url arrays
		$this->data['lang'] = \m4\m4mvc\helper\Str::getLang();
		$this->data['url']  = \m4\m4mvc\helper\Str::getUrl();

		echo $twig->render($view, $this->data);
	}
}