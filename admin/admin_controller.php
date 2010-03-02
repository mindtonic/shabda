<?php

class admin_controller extends Controller
{
	protected $credentials = array( "index" => "is_admin", 
																	"admin" => "super",
																	"show" => "super",
																	"update" => "super",
																	"destroy" => "super" );

	function index() 
	{
		$this->view->recentUsers = $this->factory->manufacture('users', array('order' => '`last_login` DESC', 'limit' => 10));
    $this->view->changeLog = $this->factory->manufacture('change_log', array('order' => '`created_at` DESC', 'limit' => 10));
	}
	
	function admin()
	{
		$admin = $this->factory->manufacture('admin', array('associations' => true, 'order' => '`order` ASC'));
		$this->view->collection = $admin;
	}

	function show()
	{
		redirect('admin', 'admin');
	}

	function update()
	{
		$admin = $this->factory->order('admin', $this->registry->id);
		$roles = $this->factory->manufacture('roles');

		if ($this->registry->request('admin'))
		{
			$admin->set_attributes($this->registry->request('admin'));
			$admin->save();

			if (!$admin->errors()) redirect('admin','admin');
		}

		$this->view->item = $admin;
	}

	function destroy()
	{
		$admin = $this->factory->order('admin', $this->registry->id);

		if ($this->registry->request('destroy') == 'true')
		{
			$admin->destroy();
			redirect('admin', 'admin');
		}

		$this->view->item = $admin;
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
					redirect('admin');
				}
				else header('location: index.php?c=users&a=validation_sent');
			}
		}

		$this->view->register('subsection', 'login');
		$this->view->item = $user;
		$this->view->layout = "plain";
	}
}


?>
