<?php
 
class Controller
{
	protected $controller = DEFAULT_CONTROLLER;
	protected $action = DEFAULT_ACTION;
	protected $registry;
	protected $factory;
	protected $view;
	protected $errors;
	protected $credentials = array();
	protected $default_credentials = array("sort" => "editor", "active_switch" => "editor");

	function __construct()
	{
		// Initialize Registry
		$this->registry = Registry::instance();
		$this->registry->initialize();
		
		// Determine Request Routing
		$this->define_shabda();
		
		// Initialize Related Objects
		$this->factory = new Factory();
		$this->view = new View($this->controller, $this->action);
		$this->errors = new Errors();
		
		// Merge Credentials
		$this->credentials = array_merge($this->default_credentials ,$this->credentials);
		
		// Activate Before Filters
		foreach (array('activate','before_filter','initialize','construct') as $method)
			if (method_exists($this, $method)) $this->$method();
		
		// Optional Informative Extensions
		if (LOG_CONTROLLER) $this->log_controller();
		if (USER_TRACKER) $this->user_tracker();	
	}

	##########################################################################
	### Controller Factory
	
	public static function get_controller()
	{
		$controller = sanitize_input($_REQUEST['c']);
	
		// Requested Controller
		if ($controller)
		{
			$requested_controller = strtolower(str_replace( array('.','/'), "", $controller));
			$controller_name = $requested_controller.'_controller';
		}
		// Default Controller
		else
		{
			$requested_controller = DEFAULT_CONTROLLER;
			$controller_name = DEFAULT_CONTROLLER.'_controller';
		}
		
		// Check for controller directory and file
		if (!is_dir($requested_controller))
			Errors::system_error('CONTROLLER: '.strtoupper($requested_controller).' PROJECT FOLDER NOT FOUND.');			
		if (!is_file($requested_controller.'/'.$controller_name.'.php'))
			Errors::system_error('CONTROLLER: '.strtoupper($requested_controller).' CONTROLLER FILE NOT FOUND.');
		
		// Include controller file
		require_once($requested_controller.'/'.$controller_name.'.php');
		
		// Check for controller class		
		if (!class_exists($controller_name))
			Errors::system_error('CONTROLLER: CONTROLLER NOT FOUND (asking for \''.$controller_name.'\').');				
			
		// Return Controller
		return new $controller_name;
	}
	
	##########################################################################
	### Define
	
	private function define_shabda()
	{
		$this->define_controller();
		$this->define_action();
		$this->check_credentials();
	}
	
	private function define_controller()
	{
		if ($this->registry->request('c'))
			$this->controller = sanitize_input($this->registry->request('c'));
			
		// Register name of current controller - default is set at the top
		$this->registry->register('controller', $this->controller);
	}
	
	private function define_action()
	{
		if ($this->registry->request('a'))
			$this->action = sanitize_input($this->registry->request('a'));
		elseif ($this->registry->request('id'))
			$this->action = 'show';
			
		// Make sure there is a controller method for the requested action.
		if (method_exists($this, $this->action))
			$this->registry->register('action', $this->action);
		else
			Errors::system_error('CONTROLLER: NO METHOD FOUND FOR THE REQUESTED ACTION (\''.$this->action.'\')');
	}
	
	##########################################################################
	### Execute
	
	public function execute()
	{
		$this->execute_action();
		$this->render();
		$this->log_housekeeping();
	}
	
	private function execute_action()
	{
		$method = $this->action;
		$this->$method();
	}
	
	public function render()
	{
		#diagnostics($this);
		$this->view->render();
	}
	
	public function log_housekeeping()
	{
		if (LOG_DATABASE_QUERIES)
		{
			$logger = new Logger('sql');
			$logger->close_entry();
		}
		if (LOG_SYSTEM_OBJECTS_LOADING)
		{
			$logger = new Logger('system');
			$logger->close_entry();
		}
	}

	##########################################################################
	### Credentials
	
	private function check_credentials()
	{
		if ($this->credentials[$this->action])
		{					
			switch ($this->credentials[$this->action])
			{
				case "all":
					return true;
					break;
				case "super":
					if ($this->registry->is_super()) return true;
					else $this->enforce_credentials();
					break;
				case "user":
					if ($this->registry->is_user()) return true;
					else $this->enforce_credentials();
					break;
				case "current_user":
					if ($this->registry->is_current_user()) return true;
					else $this->enforce_credentials();
					break;
				case "logged_in":
					if ($this->registry->is_logged_in()) return true;
					else $this->enforce_credentials();
					break;
				case "not_logged_in":
					if ($this->registry->is_not_logged_in()) return true;
					else $this->enforce_credentials();
					break;
				case "not_logged_in_unless_super":
				  if ($this->registry->is_super()) return true;
				  elseif ($this->registry->is_not_logged_in()) return true;
					else $this->enforce_credentials();
				  break;
				case "is_admin":
					if ($this->registry->is_admin()) return true;
					else $this->enforce_credentials();
					break;
				default:
					if ($this->registry->check_role($this->credentials[$this->action])) return true;
					else $this->enforce_credentials();
					break;
			}
		}
	}
	
	private function enforce_credentials()
	{
	  if ($this->registry->is_user())
	    redirect('users','access_denied');
		else
		{
			$this->registry->set_session('return',$_SERVER['REQUEST_URI']);
			redirect('accounts','login');
		}
	}

	##########################################################################
	### Default Controller Actions
	
	function update()
	{
		$object = $this->factory->order($this->controller, $this->registry->id);
			
		if ($this->registry->request($this->controller))
		{
			$object->set_attributes($this->registry->request($this->controller));
			$object->save();

			if (!$object->errors()) admin_redirect($this->controller);
		}
		
		$this->view->item = $object;
		$this->view->view = 'layouts/defaults/update.inc';
	}
	
	function sort()
	{	
		if ($_POST)
		{
      parse_str($_POST['data']);
			for ($i = 0; $i < count($sortList); $i++)
			{
				$object = $this->factory->order($this->controller, $sortList[$i]);
				$object->set_value('order', $i);
				$object->save();
			}
		}

		$model = $this->factory->manufacture($this->controller, array('order' => '`order` ASC'));
		$this->view->collection = $model;
		$this->view->layout = $_POST ? false : 'admin';
		$this->view->view = 'layouts/defaults/sort.inc';
	}
	
	function on_off_switch()
	{
		$model = $this->factory->order($this->controller, $this->registry->id);
		$model->on_off_switch($this->registry->s);
		admin_redirect($this->controller);
	}

	##########################################################################
	### Utilities
	
	public function redirect($controller, $action = null, $id = null)
	{
	  $redirect = 'location:index.php?c='.$controller.($action ? '&a='.$action : '').($id ? '&id='.$id : '');
	  if (LOG_REDIRECTS) $this->log_redirect($redirect);
		header($redirect);
		die;
	}
	
	private function log_redirect($redirect)
	{
		$logger = new Logger('system');
		$logger->enter('Redirect: '.$redirect);
	}
	
	###
	
	function log_controller()
	{
		$logger = new Logger('system');
		$logger->enter('Controller: '.$this->controller.' - Action: '.$this->action);
	}
	
	###
		
	function user_tracker()
	{
		$tracker = new user_tracker;
		if (is_logged_in())
		{
			$tracker->set_value('user_id', current_user_id());
			$tracker->set_value('guest', 0);
		}
		else
		{
			$tracker->set_value('user_id', null);
			$tracker->set_value('guest', 1);			
		}
		$tracker->set_value('controller', $this->controller);
		$tracker->set_value('action', $this->action);
		$tracker->set_value('ip', $_SERVER['REMOTE_ADDR']);
		
		if ($_SERVER['QUERY_STRING']) 
		{
			$params = array();
			foreach (explode('&', $_SERVER['QUERY_STRING']) as $param)
				if (!preg_match('/(^c=)|(^a=)/', $param)) $params[] = $param;
			$tracker->set_value('params', implode($params, ', '));
		}
		
		$tracker->save();
	}
	
	##########################################################################
}

?>
