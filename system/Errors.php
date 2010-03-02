<?php

class Errors
{

	# Model Errors

	function errors_on($attribute, Model $model)
	{
		if ($model->errors_on($attribute))
			return '<div class="errors">'.$model->errors_on($attribute).'</div>';
	}

	# System Error

	public function system_error($message)
	{
		// Set Message - stored in registry
		$registry = Registry::instance();
		$registry->set_shabda_error($message);	
		// Record Message
		if (LOG_ERRORS)
			Errors::log_shabda_error();	
		// Mail Error
		if (SEND_ERROR_MAIL)
			Errors::mail_shabda_error();
		// Display			
		Errors::show_shabda_error();
	}
	
	static public function log_shabda_error()
	{
		Logger::log_shabda_error();			
	}
	
	static public function mail_shabda_error()
	{
		Mailer::mail_shabda_error();	
	}
	
	static public function show_shabda_error()
	{
		// Redirect for friendly error message
		if (!SHOW_ERRORS || MODE == "production") 
			header('location: index.php?c=errors&a=index');
		else
		{
			// Direct output for development
			$registry = Registry::instance();
			die('<h1>Shabda Error</h1><h3>'.$registry->shabda_error().'</h3>');		
		}
	}
	
	static public function save_error()
	{
		shabda_errors::save_error();
	}

}

?>