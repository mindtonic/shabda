<?php
################################################################################
################################################################################
### USAGE EXAMPLE
/*

// IMAGE
if ($_FILES['image'][tmp_name])
{
	// Vendor Files
	require_once 'modules/images_save.php';
	// Filename & Directory			
	$image_directory = "images/sponsors/";
	$image_filename = preg_replace('/[^\w]/',NULL,$sponsor->name);				
	
	if (new images_check_directories($image_directory))
	{					
		$image = new upload_image("image");
		// Use this instead to save images directly from a url.
		#elseif ($_REQUEST[image_url]) $image = new external_image($_REQUEST[image_url]);

		// Instantiate save_images object
		$image_processor = images_save::determine_filetype($image->get_filetype(), $image);
		
		// Set Values
		$image_processor->set_directory($image_directory);
		$image_processor->set_filename($image_filename);
		$image_processor->mainWidth = 250;   // Maximum width for resizing the main image
		$image_processor->mainHeight = 250;  // Maximum height for resizing the main image						
		
		// Import Image : Original is a boolean to save the original image
		$image_processor->import_image($original);
		
		#$sponsor->set_value('original', $image_processor->original);
		$sponsor->set_value('main', $image_processor->main);
		$sponsor->set_value('thumbnail', $image_processor->thumbnail);
		$sponsor->save();
	}						
*/
### END USAGE EXAMPLE
################################################################################
################################################################################
### PARENT CLASS

abstract class images_save
{
	/* PUBLIC */
	public $aResult;          	// Variable for returning result information
	public $mainWidth = 500;   // Maximum width for resizing the main image
	public $mainHeight = 500;  // Maximum height for resizing the main image
	public $tnailWidth = 100;  // Maximum width for resizing the thumbnail image
	public $tnailHeight = 100; // Maximum height for resizing the thumbnail image
	public $jpegQuality = 80;  // Integer defining jpeg image quality (max 100)
	
	public $original;
	public $main;
	public $thumbnail;
	
	/* PROTECTED */
	protected $vDirectory;      // Saving Directory
	protected $vBaseFileName;   // File Name
	protected $vImageType;      // Image Type as File Extension
	protected $oImageObject;    // Object of original import image
	protected $iImage;          // Original Image
	protected $iTempImage;      // Temporary Image
	
	/* CONSTRUCTOR */
	function __construct(getImageObject $image, $type)
	{
		// Define imported image object
		$this->oImageObject = $image;
		// Define Image Type
		$this->vImageType = $type;
	}
	
	//----------------------------------------------------------------------------
	
	/* STATIC FUNCTIONS */
	// Determine the image filetype
	static function determine_filetype($filetype,$ProductImage)
	{
		switch($filetype)
		{
			case 'gif':
				$type ="gif";
				return new gifImage($ProductImage, $type);
				break;
			case 'png':
				$type ="png";
				return new pngImage($ProductImage, $type);
				break;
			case 'jpg':
			case 'jpeg':
				$type ="jpeg";
				return new jpegImage($ProductImage, $type);
				break;
			default:
				throw new Exception("Unsupported image type");
				break;
		}
	}
	
	//----------------------------------------------------------------------------
	
	/* PUBLIC FUNCTIONS */
	// Import Image is the machine that controls the process.
	public function import_image($original = false)
	{
	    // Get Image
		$this->iImage = $this->oImageObject->get_image();
		// Create Temp Image
		$this->create_temp_image();
		// Save Original
		if ($original)
		{
			$filename = $this->mDirectory."/".$this->mBaseFileName."_ORIGINAL.".$this->vImageType;
			$this->oImageObject->save_original_image($filename);
			$this->original = $filename;
		}
		// Resample & Save Main
		$this->resample_image("MAIN");
		// Resample & Save Tnail
		$this->resample_image("TNAIL");
	}

	public function set_directory($directory)
	{
		$this->mDirectory = $directory;
	}
	
	public function set_filename($filename)
	{
		$this->mBaseFileName = $filename;
	}
	
	public function get_directory()
	{
		return $this->mDirectory;
	}

	public function get_result()
	{
		return $this->mResult;
	}
	
	//----------------------------------------------------------------------------
	
	/* PRIVATE FUNCTIONS */
	// Resample the image based on constant values defined above
	# Original Image Object is called from outside of function
	private function resample_image($type)
	{
		// Establish maximum parameters for image size based on pre-established constants
		// and specific image purpose (type)
        if ($type == "MAIN")
        {
            $max_width = $this->mainWidth;
            $max_height = $this->mainHeight;
            $fileout = $this->mDirectory."/".$this->mBaseFileName.".".$this->vImageType;
        		$this->main = $fileout;
        }
        elseif ($type == "TNAIL")
        {
            $max_width = $this->tnailWidth;
            $max_height = $this->tnailHeight;
            $fileout = $this->mDirectory."/".$this->mBaseFileName."_TNAIL.".$this->vImageType;
        		$this->thumbnail = $fileout;
        }
        else
        {
            $this->aResult[] = "Unknown resize type";
            throw new Exception("Unknown resize type");
        }
		
		// Get the image's current dimensions
		list($width_orig, $height_orig) = @getimagesize($this->iImage);
		// Resize the image if it needs it
		if ($width_orig > $max_width || $height_orig > $max_height)
		{
			// Test the current dimensions against the maximum allowed dimensions
			if ($max_width && ($width_orig < $height_orig)) $max_width = round(($max_height / $height_orig) * $width_orig);
			else $max_height = round(($max_width / $width_orig) * $height_orig);
			// Create new image
			$new_image = imagecreatetruecolor($max_width, $max_height);
			// Resample image
			imagecopyresampled($new_image, $this->iTempImage, 0, 0, 0, 0, $max_width, $max_height, $width_orig, $height_orig);
			// Save new image
			$this->save_image($new_image, $fileout);
		}
		else $this->save_image($this->iTempImage, $fileout);	// Save original image
	}
	
	//----------------------------------------------------------------------------
	
	/* ABSTRACT FUNCTIONS */
	// Create the temp image for the process
	abstract function create_temp_image();
	// Save the new images in appropriate output
	abstract function save_image($img, $fileout);
}

### End Parent Class
################################################################################
################################################################################
### Extensions

class gifImage extends images_save
{
	function create_temp_image()
	{
		$this->iTempImage = imagecreatefromgif ($this->iImage);
	}
	
	function save_image($img, $fileout)
	{
		imagegif ($img, $fileout);
	}
}

class jpegImage extends images_save
{
	function create_temp_image()
	{
		$this->iTempImage = imagecreatefromjpeg($this->iImage);
	}

	function save_image($img, $fileout)
	{
		imagejpeg($img, $fileout, $this->jpegQuality);
	}
}

class pngImage extends images_save
{
	function create_temp_image()
	{
		$this->iTempImage = imagecreatefrompng($this->iImage);
	}

	function save_image($img, $fileout)
	{
		imagepng($img, $fileout);
	}
}

### End Extensions
################################################################################
################################################################################
### Compsition Class

abstract class getImageObject
{
	abstract function get_image();
	abstract function get_filetype();
	abstract function save_original_image($filename);
}

### End Composition Class
################################################################################
################################################################################
### Composition Class Extensions

class upload_image extends getImageObject
{
	private $imageSource;
	public $tempImage;
	
	function __construct($imageSource)
	{
		$this->imageSource = $imageSource;    // In this case Form Submission Name
		if (!$_FILES[$imageSource]['tmp_name']) throw new Exception("Uploaded file did not exist");
		if ($_FILES[$imageSource]['error']) throw new Exception("An unknown error occurred");
	}

	// Extract image object from uploaded file ($files_name is REQUEST value of upload field)
	function get_image()
	{
		// Create and return image object
	  $this->tempImage = $_FILES[$this->imageSource]['tmp_name'];
	  return $this->tempImage;
	}

	function get_filetype()
	{
		switch($_FILES[$this->imageSource]['type'])
		{
			case 'image/gif':
				return "gif";
				break;
			case 'image/x-png':
			case 'image/png':
				return "png";
				break;
			case 'image/pjpeg':
			case 'image/jpg':
			case 'image/jpeg':
				return "jpeg";
				break;
			default:
				throw new Exception("Unsupported image type. Please upload a gif, jpeg or png file");
				break;
		}
	}
	
	function save_original_image($filename)
	{
		if (!copy($this->tempImage,$filename))
			throw new Exception("Could not save original external temp image");
	}
}

// -----------------------------------------------------------------------------

class external_image extends getImageObject
{
	private $imageSource;
	private $upfile;
	public $tempImage;

	function __construct($imageSource)
	{
		$this->imageSource = $imageSource;  	// In this case the URL
		$this->get_image();
	}
	
	function get_image()
	{
        $temp = false;
        $tmpfname = tempnam("tmp/", "TmP-");
        $temp = @fopen($tmpfname, "w");
        if ($temp)
        {
            if (@fwrite($temp, @file_get_contents($this->imageSource)))
            {
                @fclose($temp);
                $this->tempImage = $tmpfname;
                return $this->tempImage;
            }
            else throw new Exception("Cannot download image");
        }
        else throw new Exception("Cannot create temp file");
	}

	function get_filetype()
	{
		if (preg_match('/\.gif/i',$this->imageSource)) return "gif";
		elseif (preg_match('/\.png/i',$this->imageSource)) return "png";
		elseif (preg_match('/\.jpg/i',$this->imageSource)) return "jpeg";
		elseif (preg_match('/\.jpeg/i',$this->imageSource)) return "jpeg";
		else throw new Exception("Unsupported image type. Please upload a gif, jpeg or png file");
	}
	
	function save_original_image($filename)
	{
		if (!copy($this->tempImage,$filename))
			throw new Exception("Could not save original external temp image");
	}
}

### End Composition Class Extensions
################################################################################
################################################################################

class images_check_directories
{
	public $success = true;          	// Variable for returning result information
	private $directory;      	// Saving Directory

	function __construct($directory)
	{
	  // Build the basic directory name
		$this->directory = $directory;
		$this->check_directories();
		return $this->success;
	}

	public function check_directories()
	{
		//Build indiviual directory names
		$tnail_dir = $this->directory."_tnails/";

		foreach(array($this->directory,$tnail_dir) as $directory)
			if (!is_dir($directory)) // Test for directory
				if (!mkdir($directory)) $this->success = false; // Try to make directory
	}
}

?>
