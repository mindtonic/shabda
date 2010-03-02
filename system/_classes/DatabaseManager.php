<?php

class DatabaseManager
{
	static private $instance = null;
	private $query_cache = array();
	public $cache = false;
	
	private function __construct() { }
	
	static public function instance()
	{
	  if (self::$instance == null)
	  	self::$instance = new DatabaseManager();
	  return self::$instance;
	}
	
	function __destruct()
	{
		if (LOG_DATABASE_QUERIES)
			$this->close_queries_log($sql);
	}
	
	### Queries

	public function dbquery($sql)
	{
		if ($this->cache) return $this->cache_query($sql);
		else return $this->do_not_cache_query($sql);
	}
	
	private function cache_query($sql)
	{
	  if (!array_key_exists($sql, $this->query_cache))
	  {
	  	if (LOG_DATABASE_QUERIES) $this->log_queries('load', $sql);
	  	if (!$this->query_cache[$sql] = mysql_query($sql))
	  		Errors::system_error("SQL ERROR:<hr />".$sql."<hr />".mysql_error());
	  }
	  elseif (LOG_DATABASE_QUERIES) $this->log_queries('cache', $sql);
		return $this->query_cache[$sql];	
	}
	
	private function do_not_cache_query($sql)
	{
		if (LOG_DATABASE_QUERIES) $this->log_queries('load', $sql);
		return $this->execute_query($sql);
	}
	
	private function execute_query($sql)
	{
		return mysql_query($sql);
	}
	
	// Proxy
	public function query($sql)
	{
		return $this->dbquery($sql);
	}
	
	// Logger Methods
	private function log_queries($method, $sql)
	{
		$logger = new Logger('sql');
		$logger->enter($method.': '.$sql);
	}
	
	private function close_queries_log()
	{
		$logger = new Logger('sql');
		$logger->close_entry();	
	}
}

?>