<?php

class Model
{
	public $table;
	public $table_data = array();
	public $image; // Must implement ImageInterface from descendant Model
	public $defaults = array();
	protected $properties = array();
	protected $results;
	protected $errors = array();

	######################################################################################	
	### Initialization

  // Label the product on instate
  function __construct($id = null)
  {
    // Set Table
    $this->table = get_class($this);
		$this->define_model();
		// Install any predefined custom attributes
		$this->custom_accessors();
    // Load Self
    if ($id) $this->load($id);
  }
	
	public function ignore_form_fields()
	{
		return array();
	}

	######################################################################################
	### Model Definitions
	
  private function define_model()
  {
		if (!$this->properties)
		{
			$sql = "SHOW FIELDS FROM `".$this->table."`";
			$data = $this->query($sql);
			if (mysql_num_rows($data))
			{
				while ($results = mysql_fetch_array($data,MYSQL_ASSOC))			
				{
					$this->properties[$results['Field']] = "";
					$this->table_data[$results['Field']] = $results['Type'];
					$this->defaults[$results['Field']] = $results['Default'];
				}
				# Strip out unset default values
				$this->defaults = array_filter($this->defaults);
			}
			else Errors::system_error('MODEL: Table '.$this->table.' does not exist.');
		}
  }

	######################################################################################
	### Getters & Setters
	
	// Custom Accessors
	protected function custom_accessors()
	{
		if ($this->accesor_attributes)
			foreach ($this->accesor_attributes as $att)
				$this->properties[$att] = "";	
	}
	
  // Set value
  public function set_value($att,$value)
  {
    if (array_key_exists($att, $this->properties))
      $this->properties[$att] = trim(stripslashes($value));
  }
  
  public function set_object($att,$object)
  {
    if (array_key_exists($att, $this->properties) && is_object($object))
      $this->properties[$att] = $object;  
  }

  // Get value
  public function value($att)
  {
    return array_key_exists($att, $this->properties) ? $this->properties[$att] : false;
  }
  
  // Unset value
  public function unset_value($att)
  {
    if (array_key_exists($att, $this->properties))
      $this->properties[$att] = null;
  }
  
	// Set Attributes for update
  public function set_attributes($attributes)
  {
  	if (is_array($attributes) and count($attributes) > 0)
  		foreach ($attributes as $key => $value)
  			$this->set_value($key,$value);
  }
  
  // Set Attributes from XML
	public function simple_xml_attributes(SimpleXMLElement $attributes)
	{
		foreach ($attributes as $key => $value)
			$this->set_value($key,$value);
	}
	
	// Errors
	public function errors()
	{
		if (isset($this->errors)) return $this->errors;
	}
	
	public function errors_on($key)
	{
		if ($this->errors[$key])
			return $this->errors[$key];
		else return false;
	}
	
	public function set_error($key, $value)
	{
		$this->errors[$key] = $value;
	}
	
	// Results
  public function results()
  {
    // Return Results
    return (isset($this->results) ? $this->results : false);
  }
  
  public function set_results($value)
  {
		$this->results = $value;
	}
	
	// Reflection
	public function is_required($field)
	{
	  if ($this->required_fields) return in_array($field, $this->required_fields) ? true : false;
	  else return false;
	}

	public function is_unique($field)
	{
	  if ($this->unique_fields) return in_array($field, $this->unique_fields) ? true : false;
		return false;
	}

	public function has_limit($field)
	{
		return ($this->field_lengths ? (array_key_exists($field, $this->field_lengths) ? $this->field_lengths[$field] : false) : false);
	}

	######################################################################################
	### Load
	
  // Load
  public function load($id)
  {
    // Query
    $sql = "SELECT * FROM `".$this->table."` WHERE `id` = '".$id."' LIMIT 1";
    $data = $this->query($sql);
    if (mysql_num_rows($data))
    {
      // Loop and assign
      foreach (mysql_fetch_array($data,MYSQL_ASSOC) as $key => $value)
      	$this->set_value($key,$value);
			// Check for onLoad functions
			if (method_exists($this, 'on_load')) $this->on_load();
      // Return
      return true;
    }
    else return false;
  }
  
  public function load_associations()
  {
  	if (is_array($this->associations))
  	{
  	  $mapper = ModelMapper::instance();
  		foreach ($this->associations as $model => $key)
				$this->properties[$model] = $mapper->load_model($model, $this->$key);
		}
  }
  
	public function load_all($associations = false)
	{
		$objects = array();
		$sql = 'SELECT * FROM `'.$this->table.'`';
		$data = $this->query($sql);
		if (mysql_num_rows($data))
		{
			while ($results = mysql_fetch_array($data))
			{
			  $object = $this->set_model($this->table, $results);
			  if ($associations) $object->load_associations();
				$objects[] = $object;
			}
		}
		return $objects;
	}
	
		public function load_active($associations = false)
	{
		$objects = array();
		$sql = 'SELECT * FROM `'.$this->table.'` WHERE `active` = 1';
		$data = $this->query($sql);
		if (mysql_num_rows($data))
		{
			while ($results = mysql_fetch_array($data))
			{
			  $object = $this->set_model($this->table, $results);
			  if ($associations) $object->load_associations();
				$objects[] = $object;
			}
		}
		return $objects;
	}
	
	############################################################################################
	### Model Mapper Proxies
	
	public function get_model($name)
	{
		$mapper = ModelMapper::instance();
		return $mapper->get_model($name);
	}

	public function load_model($name, $id)
	{
		$mapper = ModelMapper::instance();
		return $mapper->load_model($name, $id);
	}
	
	public function set_model($name, $attributes)
	{
		$model = $this->get_model($name);
		$model->set_attributes($attributes);
		return $model;
	}

	############################################################################################  
	### Save
	
  // Save Object
  public function save()
  {
    // Before Save - set in models
    if (method_exists($this, 'before_save')) $this->before_save();
    // Validate
    if (!$this->validate()) return false;
    // Format
    $this->format();
    // Assemble
    $this->is_editing() ? $this->update() : $this->create();
  }
  
  // Processing Method for a new Object
  private function create()
  {
    // Assign
    foreach ($this->properties as $key => $value)
    {
    	if (array_key_exists($key, $this->table_data) && $key != 'id')
    	{
				$columns[] = "`".$key."`";
				$data[] = "'".$this->sanitize_query($value)."'";
      }
    }
    // Assemble
    $sql = "INSERT INTO `".$this->table."` (".implode(",",$columns).") VALUES (".implode(",",$data).")";
    // Query
    $data = $this->query($sql);
    // Set ID
    $this->set_value('id',mysql_insert_id());
	}
	
	// Processing Method for an existing Object
	private function update()
	{
    // Assign
    foreach ($this->properties as $key => $value)
    	if (array_key_exists($key, $this->table_data) && $key != 'id')
				$update[] = "`".$key."` = '".$this->sanitize_query($value)."'";
    // Assemble
    $sql = "UPDATE `".$this->table."` SET ".implode(",",$update)." WHERE `id` = '".$this->id."'";
    // Query
    $data = $this->query($sql);
	}

	// Sanitize
	private function sanitize_query($value)
	{
		return trim(addslashes($value));
	}
  
  // Is Editing?
  public function is_editing()
  {
    return $this->id ? true : false;
	}
		
	############################################################################################  
	### Destroy
	
	// Destroy
	public function destroy()
	{
		// Destroy self
		$sql = "DELETE FROM `".$this->table."` WHERE `id` = '".$this->id."' LIMIT 1";
		$data = $this->query($sql);
		if (mysql_affected_rows()) $this->results = "Delete successful.";
	}
	
	// Destroy Associations - Associations must be in their own array
	public function destroy_associations($association)
	{
		if ($this->$association)
			foreach ($this->$association as $model)
				$model->destroy();
	}
  
	############################################################################################  
	### Query
	
	protected function query($sql)
	{
  	$db = DatabaseManager::instance();
		return $db->dbquery($sql);
	}
	
	protected static function static_query($sql)
	{
		$db = DatabaseManager::instance();
	  return $db->dbquery($sql);
	}

	############################################################################################
	### Utilities

	public function increment($field)
	{	
		if ($this->$field >= 0)
		{
			$sql = "UPDATE `".$this->table."` SET ".$field." = ".$field."+1 WHERE `id` = '".$this->id."' LIMIT 1";
			$data = $this->query($sql);
		}	
	}
	
	public function decrement($field)
	{
		if ($this->$field >= 0)
		{
			$sql = "UPDATE `".$this->table."` SET ".$field." = ".$field."-1 WHERE `id` = '".$this->id."' LIMIT 1";
			$data = $this->query($sql);
		}
	}
		
	// Convert boolean to tiny int
	protected function boolean($list)
	{
		foreach ($list as $bool)
			$this->set_value($bool, $this->$bool ? 1 : 0); 
	}
	
	// Switch booleans on and off easily
	public function on_off_switch($field)
	{
		$sql = "UPDATE `".$this->table."` SET `".$field."` = ".($this->$field ? 0 : 1)." WHERE id = '".$this->id."'";
		$data = $this->query($sql);
		$this->set_value($field,($this->$field ? 0 : 1));
	}
	
	public function activate()
	{
	  if (array_key_exists('active', $this->properties))
		{
			$this->set_value('active', 1);
			$this->save();
		}
	}
	
	public function deactivate()
	{
	  if (array_key_exists('active', $this->properties))
		{
			$this->set_value('active', 0);
			$this->save();
		}
	}

	############################################################################################
  ### Validate
  
  public function validate()
  {
   	// Call validations in order of lesser importance. Error message is set by final method.
		if (method_exists($this, 'custom_validations')) $this->custom_validations();   	
   	if ($this->field_lengths) $this->validate_length();
		if ($this->confirmations) $this->validate_confirmations();
  	if ($this->unique_fields) $this->validate_unique();
		// Automatically validate urls and email
		$this->validate_email();
		$this->validate_url();
		if ($this->required_fields) $this->validate_required();
		if ($this->agreements) $this->validate_agreements();
  	
  	return (count($this->errors) > 0 ? false : true);
  }
  
  private function validate_required()
  {
		foreach ($this->required_fields as $field)
			if (!isset($this->$field) || $this->$field == "")
				$this->errors[$field] = $this->message($field)." is required";
  }
  
  private function validate_unique()
  {
		foreach ($this->unique_fields as $field)
		{
			$sql = "SELECT `id` FROM `".$this->table."` WHERE `".$field."` = '".$this->$field."' AND `id` != '".$this->id."' LIMIT 1";
			$data = $this->query($sql);
			if (mysql_num_rows($data))
				$this->errors[$field] = $this->message($field)." must be unique.  Unfortunately, that one is already in use.";
		}  
  }
 
  private function validate_length()
  {
		foreach ($this->field_lengths as $field => $length)
			if (strlen($this->$field) > $length)
				$this->errors[$field] = $this->message($field)." is too long. Please limit it to ".$length." characters.";		
  }
  
  private function validate_confirmations()
  {
  	foreach ($this->confirmations as $confirmation)
  	{
  		$confirm = $confirmation.'_confirmation';
  		if ($this->$confirmation != $this->$confirm)
  		{
  			$this->errors[$confirmation] = ucwords($this->message($confirmation))." does not match.";		
  			$this->errors[$confirm] = $this->message($confirm)." does not match.";
  		}
  	}
  }
  
  private function validate_agreements()
  {
		foreach ($this->agreements as $agreement)
			if (!$this->$agreement)
				$this->errors[$agreement] = "you must agree to the ".$this->message($agreement).".";
  }

	public function validate_email()
	{
    if (array_key_exists('email', $this->properties) && $this->email)
    {
			$regex = "^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$";
			if (!eregi($regex, $this->email))
      	$this->errors['email'] = "this email address does not appear to be valid.";
		}
	}
	
	public function validate_url()
	{
		if (array_key_exists('url', $this->properties))
			$this->validate_custom_url('url');
	}
	
	protected function validate_custom_url($key)
	{
		if ($this->$key)
		{
			// Check for http and add if necessary
			if (!preg_match('/^(http|https)+(:\/\/)/i',$this->$key))
		    $this->set_value($key,'http://'.$this->$key);
			// Check url with regex
			$regex = "^(http|https)+(:\/\/)+[a-z0-9_-]+\.+[a-z0-9_-]";
			if (!eregi($regex, $this->$key))
	    	$this->errors[$key] = "this url does not appear to be valid.";	
		}
	}
  
  private function message($message)
  {
  	return ucwords(ereg_replace('_', ' ', $message));
  }
	
	############################################################################################
	### Format
	
  public function format()
  {
  	if (method_exists($this, 'custom_formatting')) $this->custom_formatting(); 
  	$this->is_editing() ? $this->updated_at() : $this->created_at();
    $this->is_editing() ? $this->created_by() : $this->edited_by();
    if ($this->titleize) $this->titleize();
    $this->health_inspector();
  }
  
  private function created_at()
  {
		if (array_key_exists('created_at', $this->properties))
			$this->set_value('created_at', date('Y-m-d H:i:s', time()) );
		// Populate updated date for sorting
		$this->updated_at();
  }
  
  private function updated_at()
  {
		if (array_key_exists('updated_at', $this->properties))
			$this->set_value('updated_at', date('Y-m-d H:i:s', time()) );
  }
  
   private function created_by()
  {
		if (array_key_exists('created_by', $this->properties))
			$this->set_value('created_by', current_user_id() );  
  }
  
  private function edited_by()
  {
		if (array_key_exists('edited_by', $this->properties))
			$this->set_value('edited_by', current_user_id() );
		elseif (array_key_exists('updated_by', $this->properties))
			$this->set_value('updated_by', current_user_id() );   
  }
  
  private function encode_entities()
  {
		foreach ($this->table_data as $key => $value)
		{
			if (strstr($value, 'varchar'))
				$this->set_value($key, htmlspecialchars($this->$key, ENT_QUOTES, 'ISO8859-15', false));
		}
	}
  
  ###
  
  // Manages data sanitizing
  protected function health_inspector()
  {
		foreach ($this->table_data as $key => $value)
		{
			if ($value == 'text')
				$this->set_value($key, $this->sanitize_text($this->$key));
			if (strstr($value, 'varchar'))
				$this->set_value($key, $this->sanitize_string($this->$key));  		
		}
  }
  
  protected function sanitize_text($text)
  {
		return strip_tags($text, '<p><a><img><div><h1><h2><h3><h4><b><u><i><ul><ol><li><br><br /><blockquote><table><th><td><tr><strong><span>');
  }
  
  protected function sanitize_string($text)
  {
		return strip_tags($text);  
  }
 
 ###

  protected function titleize()
  {
  	foreach ($this->titleize as $value)
  		$this->set_value($value, ucwords(ereg_replace('_',' ',$this->$value)));
  }
  
  ############################################################################################
  ### Image Functions
  
	public function thumbnail()
	{
		if ($this->image_object()) return $this->image->thumbnail();
	}

	public function image($class = false)
	{
		if ($this->image_object()) return $this->image->image($class);
	}

	// Image Utilities

	public function image_count()
	{
		return count($this->image());
	}

	public function image_object()
	{
	  if ($this->id)
		{
			if (!$this->image) $this->image_factory();
			return $this->image;
		}
		else return false;
	}

	public function image_factory()
	{
		// Image Class Name
		$classname = $this->table.'_images';
		// If this has an id, it can check for associations
		if ($this->id)
		{
			$sql = 'SELECT * FROM `'.$classname.'` WHERE `'.$this->table.'_id` = '.$this->id.' LIMIT 1';
			$data = $this->query($sql);
			if (mysql_num_rows($data))
			{
				while ($results = mysql_fetch_array($data))
					$object = $this->set_model($classname, $results);
			}
  	}

		if (!$object) $object = $this->new_image_object();
		$this->image = $object;
	}
	
	public function new_image_object()
	{
		$object = $this->get_model($this->table.'_images');
		$object->set_value($this->table.'_id', $this->id);
		$object->set_value('name', $this->name);
		return $object;
	}
  
  ############################################################################################
  ### Default Property Functions

	public function __set($label, $object)
	{
    if (!isset($this->properties[$label]))
    	$this->properties[$label] = $object;
	}

	public function __unset($label)
	{
    if (isset($this->properties[$label]))
    	unset($this->properties[$label]);
	}

	public function __get($label)
	{
    return (isset($this->properties[$label]) ? $this->properties[$label] : false);
	}

	public function __isset($label)
	{
    return (isset($this->properties[$label]) ? true : false);
	}
	
	######################################################################################
}

?>
