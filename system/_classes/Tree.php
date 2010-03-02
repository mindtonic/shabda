<?php

class Tree extends Model
{
	// Sub Objects
	public $parent;
	public $siblings;
	public $children;

	### Acts As Tree Methods

	public function parent()
	{
		if (!$this->parent) $this->load_parent();
		return $this->parent ? $this->parent : false;
	}

	public function load_parent()
	{
		if ($this->parent_id)
		{
			$sql = "SELECT * FROM `".$this->table."` WHERE `id` = ".$this->parent_id." LIMIT 1";
			$data = $this->query($sql);

			if (mysql_num_rows($data))
			{
				$results = mysql_fetch_array($data);
				$this->parent = $this->set_model($this->table, $results);
			}
		}
	}

	public function siblings($active = false)
	{
		if (!is_array($this->siblings)) $this->load_siblings($active);
		return $this->siblings ? $this->siblings : false;
	}

	public function load_siblings($active = false)
	{
		// Allow for a blank object to pull all root objects
		if (!$this->parent_id) $this->set_value('parent_id', 0);
		$sql = "SELECT * FROM `".$this->table."` WHERE `parent_id` = ".$this->parent_id." ".($active ? "AND active = 1" : "")." ORDER BY `order`";
		$data = $this->query($sql);

		if (mysql_num_rows($data))
		{
		  $this->siblings = array();
			while ($results = mysql_fetch_array($data, MYSQL_ASSOC))
				$this->siblings[] = $this->set_model($this->table, $results);
			return $this->siblings;
		}
	}

	public function children($active = false)
	{
		if (!is_array($this->children)) $this->load_children($active);
		return $this->children ? $this->children : false;
	}

	public function load_children($active = false)
	{
		if ($this->id)
		{
			$sql = "SELECT * FROM `".$this->table."` WHERE `parent_id` = ".$this->id." ".($active ? "AND active = 1" : "")." ORDER BY `order`";
			$data = $this->query($sql);

			if (mysql_num_rows($data))
			{
			  $this->children = array();
				while ($results = mysql_fetch_array($data, MYSQL_ASSOC))
					$this->children[] = $this->set_model($this->table, $results);
				return $this->children;
			}
		}
	}
}

?>
