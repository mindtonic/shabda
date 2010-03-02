<?php

class check_directories
{
	public $errors = array();   				// Variable for returning result information
	public $results;
	private $directory;      	// Saving Directory

	function __construct($directory)
	{
		// Clean Input
		$directory = trim(ereg_replace('/','\\',$directory));
		$directory = explode('\\',$directory);
		$directory = array_filter($directory);
				
		// Assign Values
	  $this->directory = $directory;
		
		// Check Directory
		$this->run();
		
		// Return success or failure
		return (count($this->errors) == 0 ? true : false);
	}

	public function run()
	{
		$trail = '';
		foreach ($this->directory as $folder)
		{
			$this->check_directory($trail.$folder);
			$trail .= $folder.'/';
		}
		$this->results = $trail;
	}
	
	private function check_directory($directory)
	{
		// Test for directory (If directory does not exist)
		if (!is_dir($directory)) 
			// Try to make directory (If directory is not made)
			if (!mkdir($directory))
				// Flag error
				$this->errors[] = 'Could not create '.$directory.'.'; 	
	}
}

?>
