<?php

class View
{
	public $controller;
	public $action;
	public $layout;
	public $view;
	public $helper;	
	private $vault;
	private $defaults = array('admin' => 'admin', 'update' => 'admin', 'destroy' => 'admin');

	function __construct($controller, $action)
	{
		$this->controller = $controller;
		$this->action = $action;
		$this->layout = $this->layout_link($controller);
		$this->view = $this->view_link($action);
    $this->helper = $controller.'/'.$controller.'_helper.php';
	}
	
	### Set and Get Vault
	
	function item($object)
	{
		$this->vault['item'] = $object;	
	}
	
	function collection($objects)
	{
		$this->vault['collection'] = $objects;		
	}
	
	function register($key, $value)
	{
		$this->vault[$key] = $value;	
	}
	
	public function __get($label)
	{
    return (isset($this->vault[$label]) ? $this->vault[$label] : false);
	}

	### Render
	
	public function render()
	{
		$this->load_helpers();
		$this->check_view();
		$this->check_layout();
		$this->render_to_browser();
	}

	// Helpers
	private function load_helpers()
	{
		// Include system helper functions
		$this->load_system_helpers();
		// Include application helper
		if (is_file('layouts/application_helper.php'))
			include_once('layouts/application_helper.php');
		// Include resource helper
		if (is_file($this->helper))
			include_once($this->helper);
	}
	
	function load_system_helpers()
	{
		$writer = new Writer;
		$files = $writer->read_directory('system/_functions');
		foreach ($files as $file) include_once('system/_functions/'.$file);
	}
	
	// Views
	private function check_view()
	{
		if ($this->view)
		{
			if (is_file($this->view)) return true;
			elseif (is_file($this->view_link($this->view)))
			{
				$this->view = $this->view_link($this->view);
				return true;
			}	
			else Errors::system_error('VIEW: No view found. Requested view: '.$this->view.' did not exist.');
		}
	}
	
	private function view_link($view)
	{
		return $this->controller.'/views/'.$view.'.inc';	
	}	
	
	private function check_layout()
	{
		if ($this->layout)
		{
			if (is_file($this->layout)) return true;
			elseif (is_file($this->layout_link($this->layout)))
			{
				$this->layout = $this->layout_link($this->layout);
				return true;
			}
			elseif (isset($this->defaults[$this->action]))
			{
				$this->layout = $this->layout_link($this->defaults[$this->action]);
			}
			elseif (is_file($this->layout_link(DEFAULT_LAYOUT)))
			{
				$this->layout = $this->layout_link(DEFAULT_LAYOUT);
				return true;
			}
			else Errors::system_error('VIEW: No layout found. Controller: '.$this->layout.'.');
		}
	}
	
	private function layout_link($layout)
	{
		return 'layouts/'.$layout.'.inc';
	}
	
	
	public function render_to_browser()
	{
		// Register Default Variable
		$CONTENT = '';
		
		// Pull view
		if (!include_once($this->view))
			Errors::system_error('VIEW: Could not render view '.$this->view.'.');

		if ($this->layout)
		{
			// Pull layout
			if (!include_once($this->layout))
				Errors::system_error('VIEW: Could not render layout '.$this->layout.'.');
		}
		// Pass view out directly if no layout is requested
		else $CONTENT = $content;
		
		// Output to browser
		echo $CONTENT;
	}
	
	######
	
	public function onload()
	{
		if ($this->onload) return ' onload="'.$this->onload.'"';
	}

}

?>
