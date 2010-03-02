<?php

/*
CREATE TABLE `content` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `handle` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `active` tinyint(1) NOT NULL default '1',
  `edited_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ;
*/

class content extends Model
{
	// Validations
	protected $required_fields = array('handle','content');
	protected $unique_fields = array('handle');
}

?>
