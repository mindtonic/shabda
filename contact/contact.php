<?php

/*
CREATE TABLE `contact` (
`id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`name` VARCHAR( 255 ) NOT NULL ,
`email` VARCHAR( 255 ) NOT NULL ,
`subject` VARCHAR( 255 ) NOT NULL ,
`message` TEXT NOT NULL
) TYPE = MYISAM ;
*/

class contact extends Model
{
	// Validations
	protected $required_fields = array('name','email','subject','message');  
}

?>
