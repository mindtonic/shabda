<?php

class navigation_controller extends Controller
{

	protected $credentials = array( "index" => "editor",
																	"admin" => "editor",
																	"update" => "editor",
																	"destroy" => "editor" );

	function index()
	{		
		redirect('navigation','admin');
	}
	
	function admin()
	{
		$this->view->item = new navigation;
	}
	
	function show()
	{
		$section = new navigation;
		$section->load_by_name($this->registry->request('id'));

		$this->registry->section = $section->section;
		$this->registry->subsection = $section->name;
		$this->view->item = $section;
	}
	
	function update()
	{
		$link = $this->factory->order('navigation', $this->registry->id, true);		
		$links = $this->factory->manufacture('navigation', array('order' => 'name ASC', 'null' => true));
		
		if ($this->registry->request('navigation'))
		{
			$link->set_attributes($this->registry->request('navigation'));
			$link->save();
			if (!$link->errors()) redirect('navigation','admin');
		}
		
		$this->view->item = $link;
		$this->view->register('links', $links);
	}
	
	// Must also destroy all dependant records...
	function destroy()
	{
		$link = $this->factory->order('navigation', $this->registry->id);
			
		if ($this->registry->request('destroy') == 'true')
		{
			$link->destroy();
			redirect('navigation','admin');
		}
		
		$this->view->item = $link;
	}
}

?>
