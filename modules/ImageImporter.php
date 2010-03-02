<?php

/*

API

$importer = new ImageImporter( ModelObject ,  Parameter[Array] );

	ModelObject is the object with which the image is associated.
	Parameter[Array] is a parameter => value array of features and parameters
		- handle : the value inside of the $_FILES[$handle] that holds the imported image
		- mainWidth : Maximum width for resizing the main image
		- mainHeight : Maximum height for resizing the main image
		- tnailWidth : Maximum width for resizing the thumbnail image
		- tnailHeight : Maximum height for resizing the thumbnail image
		- jpegQuality : Integer defining jpeg image quality (max 100)


directory is: parent_table_name/parent_item_id/image_model_id
example: galleries/1/5

CREATE TABLE `services_images` (
`id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`services_id` INT( 11 ) NOT NULL ,
`filename` VARCHAR( 255 ) NOT NULL ,
`filepath` VARCHAR( 255 ) NOT NULL ,
`filesize` INT( 11 ) NOT NULL ,
`mime_type` VARCHAR( 20 ) NOT NULL ,
`width` INT( 11 ) NOT NULL ,
`height` INT( 11 ) NOT NULL ,
`size_tags` VARCHAR( 100 ) NOT NULL ,
`parent_id` INT( 11 ) NULL ,
`handle` VARCHAR( 100 ) NOT NULL DEFAULT 'image',
`name` VARCHAR( 255 ) NOT NULL ,
`description` TEXT NULL ,
`order` INT( 11 ) NOT NULL DEFAULT '0',
`active` TINYINT( 1 ) NOT NULL ,
`edited_by` INT( 11 ) NOT NULL ,
`created_at` DATETIME NOT NULL ,
`updated_at` DATETIME NOT NULL
) TYPE = MYISAM ;


*/

require_once('modules/images_save.php');

class ImageImporter extends Shabda
{
	// Processor
	private $processor;

	// Object to which the image is associated
	private $image_parent;
	private $image_model;
	private $image_thumbnail;					

	// Parameters
	private $handle = 'image';				// $_FILES[$handle]
	private $mainWidth = 500;   			// Maximum width for resizing the main image
	private $mainHeight = 500;  			// Maximum height for resizing the main image
	private $tnailWidth = 100;  			// Maximum width for resizing the thumbnail image
	private $tnailHeight = 100; 			// Maximum height for resizing the thumbnail image
	private $jpegQuality = 80;  			// Integer defining jpeg image quality (max 100)
	
	// Naming
	private $image_name;
	private $image_filename;
	private $image_directory;
	
	// Reporting
	private $results = array();
	private $errors = array();
	
	
	function __construct(ImageObject $model, $parameters = array())
	{
		$this->image_model = $model;
		$this->image_parent = $this->image_model->parent_model();
		$this->image_thumbnail = $this->image_model->thumbnail_model();
		
		$this->extract_parameters($parameters);

		return $this->run();
	}
	
	private function extract_parameters($parameters)
	{
		if (is_array($parameters))
		{
			foreach ($parameters as $param => $value)
				if ($this->$param) $this->$param = $value;
		}
		else return false;
	}
	
	public function results()
	{
		if ($this->errors) return implode('<br />', $this->errors);
		elseif ($this->results) return implode('<br />', $this->results);
	}


#########


	private function run()
	{
		// Set Naming Convention
		$this->set_image_name();		
		// Check that there is data in the files array
		$this->check_files();
		
		// Check errors
		if (count($this->errors) > 0) 
			return $this->set_errors();
		
		// Open Image
		$this->image_factory();

		// Check errors
		if (count($this->errors) > 0) 
			return $this->set_errors();

		// Save Image Model for ID
		$this->image_model->save();

		// Set dirctory structure
		$this->set_directory_name();
		$this->check_directories();	
		
		// Set values in importer
		$this->set_values();
		
		// Import Image
		$this->import_image();
		
		// Register Images
		$this->register_images();
		
		#diagnostics($this);
		
		// Housekeeping
		$this->housekeeping();
		
		// Final Error Check
		if (count($this->errors) > 0) 
			return $this->set_errors();
		else return $this->set_successful();
	}
	
	private function set_errors()
	{
    $this->image_model->set_error('image', implode('<br />', $this->errors));
    return false;
	}
	
	private function set_successful()
	{
    $this->image_model->set_results('Image was imported successfully');
    return true;
	}

#########
# Naming and Directories
	
	private function set_image_name()
	{
		$this->image_filename = preg_replace('/\W/',NULL,$this->image_model->name);			
	}
	
	private function set_directory_name()
	{
		$this->image_directory = 'images/'.$this->image_parent->table.'/'.$this->image_parent->id.'/'.$this->image_model->id;	
	}
	
	private function check_directories()
	{
		$directories = new check_directories($this->image_directory);
	}



#########
# Check Files

	private function check_files()
	{
		if (!$_FILES[$this->handle]['tmp_name'])
			$this->errors[] = 'file not submitted properly';
	}

	
#########
# Import Image
	
	private function image_factory()
	{
		// Instantiate upload_images object
		$image = new upload_image("image");
		// Instantiate save_images object
		$this->processor = images_save::determine_filetype($image->get_filetype(), $image);
		// Reporting
		if (!$this->processor)
			$this->errors[] = 'file was rejected by the processor.';	
	}
		
	private function set_values()
	{
		$this->processor->set_directory($this->image_directory);
		$this->processor->set_filename($this->image_filename);
		$this->processor->mainWidth = $this->mainWidth;
		$this->processor->mainHeight = $this->mainHeight;
		$this->processor->tnailWidth = $this->tnailWidth;
		$this->processor->tnailHeight = $this->tnailHeight;
		$this->processor->jpegQuality = $this->jpegQuality;
	}
	
	private function import_image()
	{
		$this->processor->import_image();	
	}
	
#########
# Save Image Model
	
	private function register_images()
	{
		// Save Image Model
		$this->image_model->set_image_info(new image_info($this->processor->main));
		$this->image_model->save();
		// Save Thumbnail
		$this->image_thumbnail->set_value('parent_id', $this->image_model->id);
		$this->image_thumbnail->set_value('name', $this->image_model->name);
		$this->image_thumbnail->set_value('handle', 'thumbnail');
		$this->image_thumbnail->set_image_info(new image_info($this->processor->thumbnail));
		$this->image_thumbnail->save();	
	}
	
	
#########
# Housekeeping

	private function housekeeping()
	{
		// Define Directory
		$directory = $this->processor->get_directory();

		// Open Directory and Read Contents		
		if (is_dir($directory))
		{
			if ($dh = opendir($directory))
			{
				while (($file = readdir($dh)) !== false)
				{
					if (!preg_match('/^\./',$file))
					{
						// If filename exists that does not match the current filename, axe it
						if ($file == $this->image_model->filename) break;
						elseif ($file == $this->image_thumbnail->filename) break;
						else unlink($directory.'/'.$file);
					}				
				}
				closedir($dh);
			}
			else $this->errors[] = "housekeeping error: Provided directory (".$directory.") could not be opened.";
		}
		else $this->errors[] = "housekeeping error: Provided directory (".$directory.") does not exist.";				
	}	
	

}

// ------------------------------------------

class image_info
{
	public $filename;
	public $filepath;
	public $filesize;
	public $mime_type;
	public $width;
	public $height;
	public $size_tags;

	function __construct($path)
	{
		// Values
		$this->filepath = $path;
		$this->filename = basename($path);
		// Processing
		$this->image_size();
		$this->file_size();
	}
	
	function image_size()
	{
		if ($imagesize = @getimagesize($this->filepath))
		{
			$this->width = $imagesize[0];
			$this->height = $imagesize[1];
			$this->size_tags = $imagesize[3];
			$this->mime_type = $imagesize['mime'];
		}
	}
	
	function file_size()
	{
		$this->filesize = filesize($this->filepath);
	}

}



?>
