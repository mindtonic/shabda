<?php

class users extends Model implements ImageInterface
{
	public $roles = array();
	
	// Validations
	protected $required_fields = array('username','email');
	protected $unique_fields = array('username','email');
	protected $field_lengths = array('username'=>'24', 'email'=>'255', 'title'=>'5', 'first_name'=>'255', 
																	 'last_name'=>'255', 'job_title'=>'255', 'organization'=>'255', 
																	 'city'=>'255', 'country'=>'255');
	protected $confirmations = array('password');
	#protected $agreements = array('user_agreement');
	
	// Accesible Attributes
	protected $accesor_attributes = array('password', 'password_confirmation');
	
  public function ignore_form_fields()
  {
    return array('username', 'hashed_password', 'validation', 'authentication', 'salt', 
    'contact', 'user_agreement', 'last_login', 'login_count', 'active', 'super');
	}
	
	##############################################################################
	### Validations
	
	protected function custom_validations()
	{
		$this->validate_password();
		$this->validate_username();
	}
	
	private function validate_password()
	{
		if (!$this->hashed_password && !$this->password)
			$this->errors['password'] = "Password is required";	
	}
	
	private function validate_username()
	{
		if (preg_match('/\s/', $this->username))
			$this->errors['username'] = "Username cannot contain spaces.";
		if (preg_match('/\W/', $this->username))
			$this->errors['username'] = "Username cannot contain special characters.";	
	}
	
	##############################################################################
	### Loading Methods
	
	protected function on_load()
	{
		$this->load_roles();
	}
	
	public function load_roles()
	{
		$sql = 'SELECT roles.role FROM roles_users, roles
						WHERE (roles_users.user_id = '.$this->id.')
						AND (roles.id = roles_users.role_id)';
    $data = $this->query($sql);
    if (mysql_num_rows($data))
      while ($results = mysql_fetch_array($data,MYSQL_ASSOC))
      	$this->roles[] = $results['role'];
	}
	
	###
	
	public function load_by_username($username = null)
	{
	  if ($username) $this->set_value('username',$username);
		$sql = "SELECT id FROM users WHERE username = '".$this->username."' LIMIT 1";
    $data = $this->query($sql);
    if (mysql_num_rows($data))
    {
    	list($id) = mysql_fetch_array($data);
    	if ($this->load($id)) return true;
    }
    return false;
	}

	public function load_by_email($email = null)
	{
	  if ($email) $this->set_value('email',$email);
		$sql = "SELECT id FROM users WHERE email = '".$this->email."' LIMIT 1";
    $data = $this->query($sql);
    if (mysql_num_rows($data))
    {
    	list($id) = mysql_fetch_array($data);
    	if ($this->load($id)) return true;
    }
    else $this->errors['email'] = "email was not found";
    return false;
	}
	
	##############################################################################
	### Before Save
	
	protected function before_save()
	{
		if (!$this->salt) $this->create_new_salt();

		if (!$this->hashed_password)
			$this->set_value('hashed_password', $this->encrypt_password());
			
		if (!$this->validation)
			$this->set_value('validation', $this->create_validation());
			
		$this->boolean(array('contact','contact_external','user_agreement','active'));
	}
	
	private function create_new_salt()
	{
		$this->set_value('salt', ($this->id + rand()) );
	}

	private function encrypt_password()
	{
		if ($this->password && $this->salt)
			return sha1($this->password.'shabda'.$this->salt);
		else return false;
	}

	private function create_validation()
	{
		if ($this->username && $this->salt)
			return md5($this->username.'_dreamspider_'.$this->salt);
		else return false;
	}

	public function validation_url()
	{
		return $this->root_path."index.php?c=accounts&a=validate&username=".$this->username."&key=".$this->validation;
	}
	
	##############################################################################
	### Authentication
	
	public function authenticate()
	{
		if (!$this->username) $this->errors['username'] = "username is required";
		if (!$this->password) $this->errors['password'] = "password is required";
		
		if ($this->errors) return false;

		if ($this->load_by_username())
		{
			if ($this->encrypt_password() === $this->hashed_password) return true;
			else $this->errors['password'] = "password is incorrect";
		}
		else $this->errors['username'] = "username was not found";
		
		return false;
	}
	
	public function register_login()
	{
		$this->unset_value('password');
		$this->unset_value('password_confirmation');
		$this->set_value('last_login', date('Y-m-d H:i:s', time()));
		$this->set_value('login_count', $this->login_count + 1);
		$this->save();
		
		$log = new Logger('access');
  	$log->enter("LOGIN ".$this->name()." | ".$this->id." - ".$this->username."");	
	}
	
	##############################################################################
	### Validation

	public function do_validation($validation_code)
	{
		if ($this->validation === $validation_code)
		{
			$this->activate_user();
			return true;
		}
		else return false;
	}
	
	private function activate_user()
	{
		$this->set_value('active', true);
		$this->save();
	}

	##############################################################################
	###  Image Interface
	
	public function new_image_object()
	{
		$classname = $this->table.'_images';
		$object = new $classname;
		$object->set_value('users_id', $this->id);
		$object->set_value('name', $this->username);
		return $object;	
	}

	##############################################################################
	### Roles
	
	public function assign_role($role_id)
	{
		$sql = "INSERT INTO `roles_users` (`role_id`,`user_id`) VALUES ('".$role_id."', '".$this->id."')";
		$data = $this->query($sql);
	}
	
	public function remove_role($role_id)
	{
		$sql = "DELETE FROM `roles_users` WHERE `role_id` = ".$role_id." AND `user_id` = ".$this->id." LIMIT 1";
		$data = $this->query($sql);
	}

	##############################################################################
	### Utilities

	public function name()
	{
		if ($this->first_name && $this->last_name)
			return  $this->first_name." ".$this->last_name;
		else return $this->username;
	}
	
	public function location()
	{
		$location = array();
		if ($this->city) $location[] = $this->city;
		if ($this->state) $location[] = $this->state;
		if ($this->country) $location[] = $this->country;
		return implode(', ', $location);
	}
	
	public function has_role($role, $super_test = true)
	{
		if ($super_test && $this->super) return true;

		if (in_array($role, $this->roles)) return true;
		else return false;
	}

	##############################################################################
	
}

?>
