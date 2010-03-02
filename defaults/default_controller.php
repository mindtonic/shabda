<?php

class *default*_controller extends Controller
{
	protected $credentials = array( "admin" => "super", 
																	"update" => "super", 
																	"destroy" => "super" );

	function index()
	{
		$*default*s = $this->factory->manufacture('*default*s');
		$this->view->collection = $*default*s;
	}
	
	function admin()
	{
		$*default*s = $this->factory->manufacture('*default*s');
		$this->view->collection = $*default*s;
	}

	function show()
	{
		$*default* = $this->factory->order('*default*s', $this->registry->id);
		$this->view->item = $*default*;
	}
	
	function update()
	{
		$*default* = $this->factory->order('*default*s', $this->registry->id);
			
		if ($this->registry->request('*default*s'))
		{
			$*default*->set_attributes($this->registry->request('*default*s'));
			$*default*->save();

			if (!$*default*->errors()) admin_redirect('*default*s');
		}
		
		$this->view->item = $*default*;
	}
	
	function destroy()
	{
		$*default* = $this->factory->order('*default*s', $this->registry->id);
			
		if ($this->registry->request('destroy') == 'true')
		{
			$*default*->destroy();
			admin_redirect('*default*s');
		}
		
		$this->view->item = $*default*;
	}
}


?>
