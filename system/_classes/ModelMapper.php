<?php

class ModelMapper
{
	// Singleton Instance
	static private $instance = null;
	// Model Cache
	private $model_cache = array();
	// DB Manager
	private $db;
	// Load all models at start up?
	private $lazy_load = true;
	
	private function __construct() { }
	
	static public function instance()
	{
	  if (self::$instance == null)
	  {
			$mapper = new ModelMapper();
			$mapper->initialize();
			self::$instance = $mapper;
		}
		return self::$instance;
	}

	// Initialized By Factory
	public function initialize()
	{
		if ($this->db == null)
			$this->db = DatabaseManager::instance();
		if (!$this->lazy_load)
			$this->load_all_models();
	}
	
	private function load_all_models()
	{
		$sql = "SHOW TABLES FROM `".DB_NAME."`";
		$data = $this->db->query($sql);
		if (mysql_num_rows($data) > 0)
			while (list($table) = mysql_fetch_array($data))
				$this->get_model($table);
	}
	
	public function get_model($name)
	{
		if (class_exists($name))
		{
			if (!array_key_exists($name, $this->model_cache))
				$this->model_cache[$name] = new $name;
			return clone $this->model_cache[$name];
		}
	}
	
	public function load_model($name, $id)
	{
		$model = $this->get_model($name);
		$model->load($id);
		return $model;
	}
}

?>
