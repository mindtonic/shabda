<?php

class accounts_controller extends Controller
{	
	protected $credentials = array( "login" => "not_logged_in",
																	"logout" => "logged_in",
																	"admin" => "super",
																	"index" => "super" );

	function admin()
	{
	
	}

	function index()
	{
		if ($this->registry->is_logged_in())
			redirect('admin');
		else redirect('accounts','login');
	}

	function login()
	{	
		$user = new users();
		
		if ($this->registry->request('accounts'))
		{		
			$user->set_attributes($this->registry->request('accounts'));
			
			if ($user->authenticate())
			{
			  if ($user->active)
			  {
	        $user->register_login();
					$this->registry->set_user($user);
					// If session is set, return to requested page
					if ($this->registry->session('return'))
					  header('location: '.$this->registry->session('return'));
					// If user is an admin user, go to the admin page
					elseif (is_admin())
						header('location: index.php?c=admin');
					// If came from login with no session, go to homepage
					elseif (strpos($_SERVER['HTTP_REFERER'],'?c=accounts&a=login'))
					  header('location: index.php');
					// Otherwise go to wherever you came from
					else header('location: '.$_SERVER['HTTP_REFERER']);
				}
				else header('location: index.php?c=users&a=validation_sent&id='.$user->username);
			}		
		}

		$this->view->item = $user;
	}
	
	function logout()
	{
		$this->registry->logout();
		redirect('home');
	}
	
	function validate()
	{
		if ($this->registry->username && $this->registry->key)
		{
			if ($user = $this->factory->special_order('users', array("where" => "`username` = '".$this->registry->username."'")))
			{
				if ($user->do_validation($this->registry->key))
				  redirect('accounts', 'login');
				else $reason = "Your validation key was incorrect.";
			}
			else $reason = "We could not find your username.";
		}
		else $reason = "Your activation url was not valid.";

		$this->view->reason_why = $reason;
	}
	
	function authenticate_email()
	{
	
	}

	function forgot_password()
	{
	
	}
}


?>
