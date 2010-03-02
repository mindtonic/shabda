<?php

define('VERSION','1.1.8');
require_once 'Environment.php';

class Shabda
{
	function __construct()
	{	
		// Select Controller
		$controller = Controller::get_controller();	
		// Execute Action
		$controller->execute();
		// Render View
		echo $controller->render();
		// Diagnostics
		#diagnostics($controller);
	}
}




?>
