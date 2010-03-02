<?php

/*

CREATE TABLE `default_images` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `default_id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `filepath` varchar(255) NOT NULL,
  `filesize` int(11) NOT NULL,
  `mime_type` varchar(20) NOT NULL,
  `width` int(11) NOT NULL,
  `height` int(11) NOT NULL,
  `size_tags` varchar(100) NOT NULL,
  `parent_id` int(11) default NULL,
  `handle` varchar(100) NOT NULL default 'image',
  `name` varchar(255) NOT NULL,
  `description` text,
  `order` int(11) NOT NULL default '0',
  `active` tinyint(1) NOT NULL,
  `edited_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ;

*/

class default_images extends ImageObject implements ImageObjectInterface
{
	protected $required_fields = array('name');
	protected $associations = array('default' => 'default_id', "users" => "edited_by");
	
	public function parent_model()
	{
		if ($this->defaults_id)
			return new defaults($this->defaults_id);
		else return false;
	}
}

?>
