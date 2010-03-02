<?php

#
# SYSTEM FUNCTIONS
#

function diagnostics($object, $die = true)
{
	echo "<div id='diagnostics'><hr />";
	echo print_r_html($object);
	//print_r($this);  	
	echo "<hr /></div>";
	if ($die) die;
}

function print_r_html($object)
{
	$data = print_r($object,true);
	$data = str_replace( " ","&nbsp;", $data);
	$data = str_replace( "\r\n","<br>\r\n", $data);
	$data = str_replace( "\r","<br>\r", $data);
	$data = str_replace( "\n","<br>\n", $data);

	return $data;
}

function print_code($code)
{
	echo htmlentities($code);	
}

function errors_on($attribute, Model $model)
{
	if ($model->errors_on($attribute))
		return '<div class="errors">'.$model->errors_on($attribute).'</div>';
}

function redirect($controller, $action = null, $id = null, $extra = null)
{
	$redirect = 'location:index.php?c='.$controller.($action ? '&a='.$action : '').($id ? '&id='.$id : '').($extra ? '&'.$extra : '');
	if (LOG_REDIRECTS) log_redirect($redirect);
	header($redirect);
	die;
}

function admin_redirect($controller, $extra = false)
{
  redirect($controller, 'admin', false, $extra);
}

function index_redirect($controller)
{
  redirect($controller, 'index');
}

function log_redirect($redirect)
{
	$logger = new Logger('system');
	$logger->enter('Redirect: '.$redirect);
}

function sanitize_input($string)
{
	$string = trim($string);
	$patterns[0] = '/\d/';
	$patterns[1] = '/\s/';
	$patterns[2] = '/\W/';
	$replacements[2] = '';
	$replacements[1] = '';
	$replacements[0] = '';
	return preg_replace($patterns, $replacements, $string);
}

?>
