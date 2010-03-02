<?php

class ImageObject extends Model
{
	// Sub Objects
	public $parent;
	public $siblings = array();
	public $children = array();

	public $thumbnail;
	
	public function check_image()
	{
		if ($this->image()) return true;
		elseif ($this->file_data_present()) return true;
		else return false;
	}
	
	public function file_data_present()
	{
		return ($_FILES['image'][tmp_name] ? true : false);
	}
	
	######################################################################################
	# Set And Get

	public function set_image_info(image_info $image)
	{
		foreach (get_object_vars($image) as $key => $value)
			$this->set_value($key, $value);
	}

	public function thumbnail_model()
	{
		if (!$this->thumbnail) $this->load_thumbnail();
		return $this->thumbnail ? $this->thumbnail : new $this->table;
	}

	public function path_only()
	{
		$info = pathinfo($this->filepath);
		return $info['dirname'];
	}

	public function load_thumbnail()
	{
		if ($this->id)
		{
			$sql = "SELECT `id` FROM `".$this->table."` WHERE `parent_id` = ".$this->id." "."AND `handle` = 'thumbnail'";
			$data = $this->query($sql);

			if (@mysql_num_rows($data))
			{
				list($id) = mysql_fetch_array($data);
				$this->thumbnail = new $this->table($id);
			}
		}
	}

	######################################################################################
	# Image Specific Returns

	public function image($class = false)
	{
		return $this->filename ? '<img src="'.$this->filepath.'" alt="'.$this->name.'" '.$this->size_tags.' '.($class ? 'class="'.$class.'"' : '').'/>' : '';
	}

	public function thumbnail()
	{
		if (!$this->thumbnail) $this->load_thumbnail();
		return $this->thumbnail ? '<img src="'.$this->thumbnail->filepath.'" alt="'.$this->name.'" '.$this->thumbnail->size_tags.' />' : '';
	}

	######################################################################################
	### Image Specific Destroy

	public function destroy_images()
	{
		$this->destroy_physical_images();
		$this->destroy_thumbnail();
	}
	
	private function destroy_physical_images()
	{
		// Define Directory
		$directory = $this->path_only();
		// Open Directory and Read Contents
		if (is_dir($directory))
		{
			if ($dh = opendir($directory))
			{
				while (($file = readdir($dh)) !== false)
					if (!preg_match('/^\./',$file)) unlink($directory.'/'.$file);
				closedir($dh);
			}
			// Attempt to remove directory - will fail if not empty
			return (@rmdir($directory) ? true : false);
		}
		return false;
	}
	
	private function destroy_thumbnail()
	{
		if (!$this->thumbnail) $this->load_thumbnail();
		if ($this->thumbnail) $this->thumbnail->destroy();
	}
}

?>
