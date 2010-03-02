<?php

class content_controller extends Controller
{
	protected $credentials = array( "admin" => "editor",
																	"show" => "editor", 
																	"update" => "editor", 
																	"destroy" => "editor" );

	function index()
	{
		redirect('content','admin');
	}
	
	function admin()
	{
		$content = $this->factory->manufacture('content');
		$this->view->collection = $content;
	}

	function show()
	{
		$content = $this->factory->order('content', $this->registry->id);
		$this->view->item = $content;
	}
	
	function update()
	{
		$content = $this->factory->order('content', $this->registry->id);
			
		if ($this->registry->request('content'))
		{
			$content->set_attributes($this->registry->request('content'));
			$content->save();
			if (!$content->errors()) redirect('content','admin');
		}
		
		$this->view->item = $content;
	}
	
	function destroy()
	{
		$content = $this->factory->order('content', $this->registry->id);
			
		if ($this->registry->request('destroy') == 'true')
		{
			$content->destroy();
			redirect('content','admin');
		}
		
		$this->view->item = $content;
	}
}


?>
