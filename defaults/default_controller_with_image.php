<?php

class default_controller extends Controller
{
	protected $credentials = array( "admin" => "super", 
																	"update" => "super", 
																	"destroy" => "super" );

	function index()
	{
		$defaults = $this->factory->manufacture('defaults');
		$this->view->collection = $defaults;
	}
	
	function admin()
	{
		$defaults = $this->factory->manufacture('defaults');
		$this->view->collection = $defaults;
	}

	function show()
	{
		$default = $this->factory->order('defaults', $this->registry->id);
		$this->view->item = $default;
	}
	
	function update()
	{
		$default = $this->factory->order('defaults', $this->registry->id);
			
		if ($this->registry->request('defaults'))
		{
			$default->set_attributes($this->registry->request('defaults'));
			$default->save();

			if (!$default->errors())
			{
				$default_image = $default->image_object();
				if (new ImageImporter( $default_image, array('mainWidth' => '200', 'mainHeight' => '300') ))
					redirect('defaults','admin');
				else $this->registry->register('report', $importer->results);
			}
		}
		
		$this->view->item = $default;
	}
	
	function destroy()
	{
		$default = $this->factory->order('defaults', $this->registry->id);
			
		if ($this->registry->request('destroy') == 'true')
		{
			$default_image = $default->image_object();
      $default_image->destroy_images();
		  $default_image->destroy();
			$default->destroy();
			admin_redirect('sponsors');
		}
		
		$this->view->item = $default;
	}
}


?>
