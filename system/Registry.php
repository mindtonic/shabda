<?php

/**
 *  USAGE EXAMPLE
 *
 *	// instantiate the registry using Singleton method
 *	$registry = Registry::instance();
 *
 **/

class Registry
{
	static private $instance = null;
	public $browser;
	private $request = array();
	private $session = array();
	private $vault = array();
	private $errors = array();
	private $shabda_error;
	public $user;
	private $session_identity = SESSION_ID;

	######################################################################################	
	### Initialization

	private function __construct() { }

	static public function instance()
	{
	  if (self::$instance == null)
	  	self::$instance = new Registry();
	  return self::$instance;
	}
	
	public function initialize()
	{
		$this->get_request();
		$this->get_session();
		$this->check_browser();
		$this->check_login();
	}

	private function get_request()
	{
		#diagnostics($_SERVER);
		if ($_SERVER['REQUEST_METHOD'])
		{
			$this->request = $_REQUEST;
			return;
		}
		
		foreach ($_SERVER['argv'] as $arg)
		{
			if (strpos( $arg, '='))
			{
				list ($key, $value) = explode ('=', $arg);
				$this->request[$key] = $value;
			}
		}
	}
	
	private function get_session()
	{
		session_register($this->session_identity);
		if ($_SESSION[$this->session_identity])
			$this->session = $_SESSION[$this->session_identity];
	}
	
	private function check_browser()
	{
		if (eregi('firefox', $_SERVER['HTTP_USER_AGENT']))
			$this->browser = 'firefox';
		elseif (eregi('MSIE 6', $_SERVER['HTTP_USER_AGENT']))
			$this->browser = 'ie6';
		elseif (eregi('MSIE 7', $_SERVER['HTTP_USER_AGENT']))
			$this->browser = 'ie7';
		elseif (eregi('safari', $_SERVER['HTTP_USER_AGENT']))
			$this->browser = 'safari';
		elseif (eregi('iPhone', $_SERVER['HTTP_USER_AGENT']))
			$this->browser = 'iPhone';
		else $this->browser = $_SERVER['HTTP_USER_AGENT'];
	}
	
	private function check_login()
	{
	  $mapper = ModelMapper::instance();
		if ($this->session['login'] && $user = $mapper->load_model('users', $this->session['login']))
			$this->set_user($user);
		else return false;
	}
	
	######################################################################################	
	### Setters & Getters
	
  public function register($label, $object)
	{
    if(!isset($this->vault[$label]))
    	$this->vault[$label] = $object;
  }	
	
	public function request($key)
	{
    return ($this->request[$key] ? $this->request[$key] : false);
	}
	
	public function unregister($label)
	{
		if (isset($this->vault[$label]))
    	unset($this->vault[$label]);
	}
	
	public function attach($label, $value)
	{
    $this->vault[$label][] = $value;		
	}

	######################################################################################
	### Session
	
	public function set_session($label, $value)
	{
		$this->session[$label] = $value;
	}
	
	public function session($label)
	{
		if (isset($this->session[$label]))
		  return $this->session[$label];
	}
	
	public function unset_session($label)
	{
		if (isset($this->session[$label]))
		  $this->session[$label];
	}
			
	######################################################################################	
	### Authentication
	
	public function set_user(users $user)
	{
		$this->user = $user;
		$this->session['login'] = $user->id;
	}
	
	public function user()
	{
	  $mapper = ModelMapper::instance();
		if ($this->user) return $this->user;
		else return $mapper->get_model('users');
	}
	
	public function user_id()
	{
		if ($this->user) return $this->user->id;	
		else return null;
	}

	public function logout()
	{
		$this->session = null;
		$this->user = null;
	}

  public function is_logged_in()
  {
  	return $this->user ? true : false;
  }
  
  public function is_not_logged_in()
  {
		return !$this->user ? true : false;
	}

  public function is_user()
  {
  	return $this->user ? true : false;
  }	
  
  public function is_super()
  {
  	return $this->user->super ? true : false;
  }
  
  public function is_admin()
  {
    if (is_super()) return true;
    elseif (count($this->user->roles)) return true;
    else return false;
	}

  public function is_moderator()
  {
  	return $this->check_role('moderator');
  }

  public function is_editor()
  {
  	return $this->check_role('editor');
  }
    
  public function is_current_user()
  {
  	if (!$this->is_logged_in())
  		return false;
  	elseif ($this->is_super())
  		return true;
  	else
  		return ($this->user->username == $this->id) ? true : false;  
  }
  
  public function check_role($role)
  {
  	if (!$this->is_logged_in())
  		return false;
  	elseif ($this->is_super())
  		return true;
  	else
  		return $this->user->has_role($role) ? true : false;
  }
  


	######################################################################################	
	### Results
  
  public function set_result($message)
  {
  	$this->session['results'][] = $message;
  }
  
  public function result()
  {
  	if (isset($this->session['results']))
  		return $this->session['results'];
  }
  
  public function results_string($seperator = "<br />")
  {
  	$return = "";
  	if (isset($this->session['results']))
  		$return = implode($seperator, $this->session['results']);

		return $return;
  }

	######################################################################################	
	### Feedback
 
  public function set_feedback($message)
  {
  	$this->session['feedback'] = $message;
  }
  
  public function feedback()
  {
  	if ($this->session['feedback'])
  		return $this->session['feedback'];  	
  }
  
	######################################################################################	
	### Errors
  
  public function set_errors($message, $field = null)
  {
  	if ($field) $this->errors[$field] = $message;
  	else array_push($this->errors, $message);
  }
  
  public function errors()
  {
  	if (isset($this->errors))
  		return $this->errors;
  }
  
  public function errors_string($seperator = "<br />")
  {
  	if (isset($this->errors))
  		return implode ($seperator, $this->errors);
  }

	######################################################################################	
	### Shabda Errors
  
  public function set_shabda_error($message)
  {
  	$this->set_session('shabda_error', $message);
  	shabda_errors::save_error();
  }
  
  public function shabda_error()
  {
  	if ($this->session('shabda_error'))
  		return $this->session('shabda_error');  
  } 

	######################################################################################	
	### Destruct
  
	function __destruct()
	{
	  // Unset login redirect if logged in
	  if (isset($this->session['login']) && isset($this->session['return']))
	    unset($this->session['return']);
		if (isset($this->session))
			$_SESSION[$this->session_identity] = $this->session;
		else
			$_SESSION[$this->session_identity] = null;
	}  

	######################################################################################	
	### Default Methods

	public function __set($label, $object)
	{
    if (!isset($this->vault[$label]))
    	$this->vault[$label] = $object;
	}

	public function __unset($label)
	{
    if (isset($this->vault[$label]))
    	unset($this->vault[$label]);
	}

	public function __get($label)
	{
    if (isset($this->vault[$label]))
    	return $this->vault[$label];
    elseif (isset($this->request[$label]))
    	return $this->request[$label];
    else return false;
	}

	public function __isset($label)
	{
    return (isset($this->vault[$label]) ? true : false);
	}
}

###########

// Permissions Proxies

function is_current_user()
{
	$registry = Registry::instance();
	return $registry->is_current_user();
}

function is_logged_in()
{
	$registry = Registry::instance();
	return $registry->is_logged_in();
}

function is_user()
{
	$registry = Registry::instance();
	return $registry->is_user();
}	

function is_super()
{
	$registry = Registry::instance();
	return $registry->is_super();
}

function is_admin()
{
	$registry = Registry::instance();
	return $registry->is_admin();
}

function is_moderator()
{
	$registry = Registry::instance();
	return $registry->is_moderator();
}

function is_editor()
{
	$registry = Registry::instance();
	return $registry->is_editor();
}

function current_user_id()
{
	$registry = Registry::instance();
	return $registry->user->id;
}

function check_user(users $user)
{
	$registry = Registry::instance();
	if (is_super()) return true;
	elseif ($user == $registry->user()) return true;
	else return false;
}

function current_user()
{
  $registry = Registry::instance();
  return $registry->user();
}

function has_role($role)
{
  $registry = Registry::instance();
  return $registry->check_role($role);
}

function is_explorer()
{
  $registry = Registry::instance();
  return eregi('ie', $registry->browser) ? true : false;
}

?>
