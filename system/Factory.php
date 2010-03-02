<?php

class Factory
{
	static public $paginator;

	// Generic Find Function to handle all possible options
	static public function Find($model, $params = null, $associations = false)
	{
		// Default Values
		$sql_where = "";
		$sql_group = "";
		$sql_order = "";
		$sql_limit = "";
		$sql_paging = false;
	
		if (is_array($params))
		{
			extract ($params);
			$sql_where = $where ? " WHERE ".$where : "";
			$sql_group = $group ? " GROUP BY ".$group : "";
			$sql_order = $order ? " ORDER BY ".$order : "";
			$sql_limit = $limit ? " LIMIT ".$limit : "";
			$sql_paging = $paging ? true : false;
			$associations = $associations ? true : false;
			$letters = $letters ? $letters : false;
			$array = $array ? true : false;
		}
		elseif (is_numeric($params))
		{
      $sql_where = " WHERE `id` = ".$params;
		}

		// Build SQL Query
		$sql = "SELECT * FROM `".strtolower($model)."`".$sql_where.$sql_group.$sql_order.$sql_limit;

		// Activate pagination if requested
		if ($sql_paging)
		{
			self::$paginator = new Paginator($model, $letters);
			self::$paginator->query($sql);
			$data = self::$paginator->results();
			
		}
		else $data = Factory::query($sql);

		if (@mysql_num_rows($data))
		{
		  $collection = array();
			while ($results = mysql_fetch_array($data,MYSQL_ASSOC))
			{
				$object = Factory::get_model($model);
				$object->set_attributes($results);
				if ($associations) $object->load_associations();
				$collection[] = $object;
			}
		}
		else
		{
  		if (!IGNORE_NULL_DATABASE_RETURNS)
				Errors::system_error('FACTORY ERROR: Requested item could not be found. SQL: '.$sql);
		}

		// If the collection array only has one object, return that object
		if (count($collection) == 1)
		{
			if ($array) return $collection;
			else return $collection[0];
		}
		// If the collection has more than one, return the array of objects
		elseif (count($collection) > 1)
		  return $collection;
		// Reporting if activated
		else return false;
		# Ruby example of returning object or array
		# multi.responses.size == 1 ? multi.responses.values.first : multi.responses.values
	}
	
	###
	
	static private function query($sql)
	{
	  $db = DatabaseManager::instance();
		return $db->dbquery($sql);
	}

	// Refer to model mapper to get original blank model
	static private function get_model($name)
	{
	  $mapper = ModelMapper::instance();
		return $mapper->get_model($name);
	}
	
	###########################################################################
	### PROXIES FOR THE OLD WAY OF DOING THINGS
	
	// Return a single object
	public function order($model, $id = null, $associations = false)
	{
	  if ($id) return Factory::Find($model, $id, $associations);
	  else return Factory::get_model($model);
	}
	
	// Return a single object
	public function special_order($model, $params = null)
	{
		return Factory::Find($model, $params);
	}

	// Return a collection of objects
	public function manufacture($model, $params = null)
	{
		$collection = Factory::Find($model, $params);
		if (is_array($collection)) return $collection;
		elseif (is_object($collection)) return array($collection);
		else return false;
	}
	
	##############################
	
	static public function set_array($objects)
	{
		if (!is_array($objects)) return array($objects);
		else return $objects;
	}
}

?>
