<?php

class shabda_errors_controller extends Controller
{
	protected $credentials = array( "admin" => "super", "show" => "super", "destroy" => "super" );
	
	function admin()
	{
		$shabda_errors = $this->factory->manufacture('shabda_errors', array('paging' => true, 'order' => '`created_at` DESC'));
		$this->view->collection = $shabda_errors;
		$this->view->paginator = Factory::$paginator;
	}
	
	function show()
	{
		$shabda_error = $this->factory->order('shabda_errors', $this->registry->id);
		$this->view->item = $shabda_error;
		$this->view->layout = "admin";
	}
	
	function destroy()
	{
		$shabda_error = $this->factory->order('shabda_errors', $this->registry->id);
			
		if ($this->registry->request('destroy') == 'true')
		{
			$shabda_error->destroy();
			admin_redirect('shabda_errors');
		}
		
		$this->view->item = $shabda_error;
	}

}


?>
