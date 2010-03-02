<?php

class database
{
	private $db;
	private $password;
	private $hash = "19443ba63f4bf9d9f0d32dbbfa815951";
	private $valid = false;
	private $lock = true;
	
	function __construct($password = false)
	{
		$this->db = DatabaseManager::instance();
		$this->password = md5($password);
		$this->validate_query();
	}

	public function is_locked()
	{
		return $this->lock ? false : true;
	}

	###

	public function valid_query($sql)
	{
	  if ($this->valid && !$this->lock)
			return $this->db->dbquery($sql);
		else
		{
			echo "<script>alert('You don\'t have permission to do that!')</script>";
			die();
		}
	}
	
	public function query($sql)
	{
    return $this->db->dbquery($sql);
	}

	###

	private function validate_query()
	{
		$this->valid = ($this->password == $this->hash ? true : false);
	}
}

?>
