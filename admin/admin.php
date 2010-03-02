<?php

/*
CREATE TABLE `admin` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(20) NOT NULL,
  `controller` varchar(100) NOT NULL,
  `active` tinyint(1) NOT NULL default '1',
  `order` int(3) NOT NULL default '999',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 ;
*/

class admin extends Model
{
	// Validations
	protected $required_fields = array('name');
	protected $field_lengths = array('name' => 20, 'controller' => 100);
	// Associations
	protected $associations = array('roles' => 'role_id');
	
	public function controller()
	{
		if ($this->controller) return $this->controller;
		else return $this->name;
	}
	
	public function load_active_admin()
	{
	  // Load array of all roles, indexed by ID - Greatly reduces queries
	  $roles = roles::load_roles();
	
		$sql = "SELECT * FROM `admin` WHERE `active` = '1' ORDER BY `order` ASC";
		$data = $this->query($sql);
		if (@mysql_num_rows($data))
		{
		  $results = array();
			while ($result = mysql_fetch_array($data))
			{
				$model = $this->set_model('admin', $result);
				// Assign role from roles array based on role_id
				$model->roles = $roles[$model->role_id];
				$results[] = $model;
			}
			return $results;
		}
		return false;
	}
}

?>
