<?php

class Writer
{
	########################################################################
	### Variables

	private $filename;
	private $directory;									// Submitted Directory
	private $root_directory;						// Created by directory analyzer
	private $sub_directories = array();	// Created by directory analyzer
	private $filecontent;

	########################################################################
	### Initialization
	
	public function __construct($filename = null, $directory = null, $filecontent = null)
	{
		if ($filename) $this->filename($filename);
		if ($directory) $this->directory($directory);
		if ($filecontent) $this->filecontent($filecontent);
		if ($filename && $directory && $filecontent) $this->write_manager();
	}
	
	########################################################################
	### Setters & Getters
	
	public function set($att, $value)
	{
		if (property_exists($this, $att))
			$this->$att = $value;
	}

	public function set_value($att, $value)
	{
		$this->set($att, $value);
	}
	
	public function filename($filename)
	{
		$this->filename = ereg_replace('/', '', $filename);
	}
	
	public function directory($directory)
	{
		$this->directory = $directory;
		$this->analyze_directory();
	}
	
	public function filecontent($content)
	{
		$this->filecontent = $content;
	}
	
	public function file_path()
	{
		$directory = $this->root_directory;
		for ($i = 0; $i <= count($this->sub_directories); $i++)
			if ($this->sub_directories[$i])
				$directory .= '/'.$this->sub_directories[$i];
		$directory .= '/'.$this->filename;
		return $directory;
	}

	########################################################################
	### File Writer
	
	// External Save Trigger
	public function save_file()
	{
		$this->write_manager();
	}

	private function write_manager()
	{
		$this->directories_manager();	
		$this->write_file();	
	}
	
	private function write_file()
	{
		$path = $this->file_path();
		if ($handle = fopen($path, 'w'))
		{
			fwrite($handle, $this->filecontent);
			fclose($handle);
		}
		else $this->system_error('WRITER: Could not open file for writing.');
	}

	########################################################################
	### Directory Manager

	private function directories_manager()
	{		
		if (!$this->root_directory)
			$this->analyze_directory();
		$this->check_root_directory();
		$this->check_sub_directories();
	}
	
	private function analyze_directory()
	{
		$this->sub_directories = preg_split('/\//',$this->directory);
		$this->root_directory = array_shift($this->sub_directories);		
	}
	
	private function check_root_directory()
	{
		$this->check_directory($this->root_directory);
	}

	private function check_sub_directories()
	{
		if ($this->sub_directories)
			foreach ($this->sub_directories as $depth => $sub)
				if ($sub)
				{
					$directory = $this->root_directory;
					for ($i = 0; $i <= $depth; $i++)
						$directory .= '/'.$this->sub_directories[$i];
					$this->check_directory($directory);
				}
	}
	
	private function check_directory($directory)
	{
		// Test for directory (If directory does not exist)
		if (!is_dir($directory)) 
			// Try to make directory (If directory is not made)
			if (!mkdir($directory))
				// Flag error
				$this->errors = 'Could not create '.$directory.'.'; 	
	}

	########################################################################
	### Delete File	
	
	public function destroy_file()
	{
		if ($this->filename && $this->directory)
			if (is_file($this->file_path()))
				unlink($this->file_path());
	}

	########################################################################
	### Read Directory Contents
	
	public function read_directory($directory)
	{
		$results = array();
		
		if (is_dir($directory))
		{
			if ($dh = opendir($directory))
			{
					while (($file = readdir($dh)) !== false)
						if (!preg_match('/^\./',$file)) $results[] = $file;
					closedir($dh);
			}
			else $results['errors'] = "Writer::read_directory error: Provided directory (".$directory.") could not be opened.";
		}
		else $results['errors'] = "Writer::read_directory error: Provided directory (".$directory.") does not exist.";
		
		return $results;
	}
		
	########################################################################
}

?>