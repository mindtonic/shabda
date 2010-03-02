<?php

class ClassLoadingObserver
{
	static private $instance = null;
	private $classes = array();
	
	private function __construct() { }

	static public function instance()
	{
	  if (self::$instance == null)
	  	self::$instance = new ClassLoadingObserver();
	  return self::$instance;
	}
	
	public function monitor_class($class)
	{
		$this->classes[] = $class;

    if (LOG_SYSTEM_OBJECTS_LOADING)
			$this->log_classes($class);
	}

  function log_classes($class)
	{
		$log = new Logger('system');
		$log->enter($class);
	}

	function classes()
	{
		return implode('<br />', $this->classes);
	}
}

?>