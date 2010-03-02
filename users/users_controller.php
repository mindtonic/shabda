<?php

class users_controller extends Controller
{
	protected $credentials = array( "admin" => "super",
																	"update" => "current_user", 
																	"destroy" => "super",
																	"register" => "not_logged_in_unless_super",
																	"roles" => "super",
																	"index" => "super",
																	"add_role" => "super",
																	"remove_roll" => "super" );


	function admin()
	{
		$this->view->collection = Factory::Find('users', array('paging' => true, 'letters' => 'username'));
		$this->view->paginator = Factory::$paginator;
	}
																	
	function index()
	{
		redirect('users','admin');
	}
	
	function show()
	{
		$user = $this->factory->order('users', array("where" => (is_numeric($this->registry->id) ? "`id`" : "`username`")." = '".$this->registry->id."'"));
		$this->view->item = $user;
	}
	
	function update()
	{
		$user = $this->factory->order('users', array("where" => (is_numeric($this->registry->id) ? "`id`" : "`username`")." = '".$this->registry->id."'"));
		if ($this->registry->request('users'))
		{
			$user->set_attributes($this->registry->request('users'));
			$user->save();

			if (!$user->errors())
			{
				$user_image = $user->image_object();
				if (new ImageImporter( $user_image, array('mainWidth' => '200', 'mainHeight' => '300') ))
					redirect('users',null,$user->username);
				else $this->registry->register('report', $importer->results);
			}
		}
		
		$this->view->item = $user;
		$this->view->layout = DEFAULT_LAYOUT;
	}	

	function destroy()
	{
		$user = $this->factory->order('users', $this->registry->id);
			
		if ($this->registry->request('destroy') == 'true')
		{
			$user->destroy();
			header('location: ?c=users');
		}
		
		$this->view->item = $user;
	}

	#######

	function register()
	{
		$user = new users();
		
		if ($this->registry->request('users'))
		{
			$user->set_attributes($this->registry->request('users'));
			$user->set_value('active', REQUIRE_EMAIL_VALIDATION ? 0 : 1);
			$user->save();
			if (!$user->errors())
			{
			  if (REQUIRE_EMAIL_VALIDATION)
			  {
					$mailer = new Mailer;
					$mailer->send_email_validation($user);
					is_super() ? redirect('users', 'admin') : redirect('users', 'validation_sent');
				}
				is_super() ? redirect('users', 'admin'): redirect('accounts', 'login');
			}
		}

		$this->view->item = $user;
	}
	
	#######
	
	function roles()
	{
		$user = $this->factory->special_order('users', array("where" => "`username` = '".$this->registry->id."'"));
		$user->load_roles();
		$this->view->item = $user;
		
		$roles = $this->factory->manufacture('roles');
		$this->view->roles = $roles;
		
		$this->view->layout = 'admin';
	}
	
	function add_role()
	{
		$user = $this->factory->special_order('users', array("where" => "`username` = '".$this->registry->id."'"));
		$role = $this->factory->special_order('roles', array("where" => "`id` = '".$this->registry->role."'"));
		
		$user->assign_role($role->id);
		redirect('users','roles',$user->username);
	}
	
	function remove_role()
	{
		$user = $this->factory->special_order('users', array("where" => "`username` = '".$this->registry->id."'"));
		$role = $this->factory->special_order('roles', array("where" => "`id` = '".$this->registry->role."'"));
		
		$user->remove_role($role->id);
		redirect('users','roles',$user->username);	
	}
	
	#######
	
	function validation_sent()
	{

	}
	
	function resend_validation()
	{
	  $user = new users();
		if ($request = $this->registry->request('users'))
		{
      if ($user->load_by_email($request['email']))
      {
				$mailer = new Mailer;
				$mailer->send_email_validation($user);
				redirect('users', 'validation_sent');
			}
			else $user->set_error('email', 'That email address was not found.');
		}
		$this->view->item = $user;
	}
	
	function welcome()
	{
	
	}
	
	function thanks()
	{
	
	}
	
	function access_denied()
	{
	
	}
	
	function incorrect_validation()
	{
	
	}
}

?>
