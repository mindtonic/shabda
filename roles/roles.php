<?php

/*
CREATE TABLE `roles` (
  `id` int(11) NOT NULL auto_increment,
  `role` varchar(20) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `roles_users` (
  `role_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY  (`role_id`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
*/

class roles extends Model
{
	// Validations
	protected $required_fields = array('role');
	protected $field_lengths = array('role' => 20);
	
	public function display_name()
	{
		return $this->role;
	}
	
	static public function load_roles()
	{
		$sql = "SELECT * FROM `roles`";
		$data = Model::static_query($sql);
		if (@mysql_num_rows($data))
		{
		  $mapper = ModelMapper::instance();
		  $results = array();
			while ($result = mysql_fetch_array($data))
			{
			  $model = $mapper->get_model('roles');
				$model->set_attributes($result);
				$results[$model->id] = $model;
			}
			return $results;
		}
		return false;
	}
}

?>
