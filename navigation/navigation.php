<?php

class navigation extends Tree
{
	// Validations
	protected $required_fields = array('name');
	protected $field_lengths = array('name'=>20, 'description'=>100, 'controller'=>100,
																	 'action'=>100, 'item'=>15, 'section'=>50, 'subsection'=>50);

	######################################################################################	
	### Loading Methods

	public function load_associations()
	{
		if ($this->id)
		{
			$this->load_parent();
			$this->load_siblings();
			$this->load_children();
		}
	}
	
	public function load_by_name($name)
	{
		$sql = "SELECT * FROM `navigation` WHERE name = '".$name."' LIMIT 1";
		$data = $this->query($sql);
		if (mysql_num_rows($data))
		{
			$results = mysql_fetch_array($data);
			if ($this->set_model($results)) return true;
		}
		return false;  
	}
	
	public function pull_current_model()
	{
		# Instate Registry
		$registry = Registry::instance();

		# Define the navigation object that is sought
		$where = array();
		$where[] = "`controller` = '".($registry->c ? $registry->c : DEFAULT_CONTROLLER)."'";
		if ($registry->a) $where[] = "`action` = '".$registry->a."'";
		if ($registry->id) $where[] = "`item` = '".$registry->id."'";

		# Pull requested navigation object
		$sql = "SELECT * FROM `navigation` WHERE ".implode(' AND ',$where)." LIMIT 1";

		#diagnostics($sql);
		$data = $this->query($sql);
		if (@mysql_num_rows($data))
		{
		  $results = mysql_fetch_array($data);
			return $this->set_model('navigation', $results);
		}
		else return false;
	}

	######################################################################################	
	### Output Methods

	public function address($encode = true)
	{
		if ($this->link) return $this->link;
		elseif ($this->controller)
		{
			$output = "";
			$output .= "index.php?c=".$this->controller;
			if ($this->action)
				$output .= ($encode ? '&amp;' : '&')."a=".$this->action;
			if ($this->item)
				$output .= ($encode ? '&amp;' : '&')."id=".$this->item;		
			return $output;
		}
		else return "index.php";
	}
	
}

?>
