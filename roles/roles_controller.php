<?php

class roles_controller extends Controller
{
	protected $credentials = array( "admin" => "super",
																	"update" => "super", 
																	"destroy" => "super" );

	function index()
	{
		redirect('roles', 'admin');
	}
	
	function admin()
	{
		$roles = $this->factory->manufacture('roles');
		$this->view->collection = $roles;
		$this->view->layout = 'admin';
	}

	function show()
	{
		redirect('roles', 'admin');
	}
	
	function update()
	{
		$role = $this->factory->order('roles', $this->registry->id);
			
		if ($this->registry->request('roles'))
		{
			$role->set_attributes($this->registry->request('roles'));
			$role->save();

			if (!$role->errors()) redirect('roles','admin');
		}
		
		$this->view->item = $role;
		$this->view->layout = 'admin';
	}
	
	function destroy()
	{
		$role = $this->factory->order('roles', $this->registry->id);
			
		if ($this->registry->request('destroy') == 'true')
		{
			$role->destroy();
			redirect('roles', 'admin');
		}
		
		$this->view->item = $role;
		$this->view->layout = 'admin';
	}
}


?>
