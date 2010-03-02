<?php

class change_log_controller extends Controller
{
	protected $credentials = array( "admin" => "super", 
																	"update" => "super", 
																	"destroy" => "super" );

	function index()
	{
		$change_log = $this->factory->manufacture('change_log', array('order' => 'created_at DESC'));
		$this->view->collection = $change_log;
	}
	
	function admin()
	{
		$change_log = $this->factory->manufacture('change_log', array('order' => 'created_at DESC'));
		$this->view->collection = $change_log;
	}

	function show()
	{
		$change_log = $this->factory->order('change_log', $this->registry->id);
		$this->view->item = $change_log;
	}
	
	function update()
	{
		$change_log = $this->factory->order('change_log', $this->registry->id);
		if (!$change_log->version) $change_log->set_value('version', VERSION);
			
		if ($this->registry->request('change_log'))
		{
			$change_log->set_attributes($this->registry->request('change_log'));
			$change_log->save();

			if (!$change_log->errors()) admin_redirect('change_log');
		}
		
		$this->view->item = $change_log;
	}
	
	function destroy()
	{
		$change_log = $this->factory->order('change_log', $this->registry->id);
			
		if ($this->registry->request('destroy') == 'true')
		{
			$change_log->destroy();
			admin_redirect('change_log');
		}
		
		$this->view->item = $change_log;
	}
}


?>
