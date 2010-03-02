<?php

class home_controller extends Controller
{
	function before_filter()
	{
		$this->view->active_menu_item = "home";
	}

	function index()
	{
		$this->view->news = $this->factory->manufacture('news', array('order' => '`sticky` DESC , `created_at` DESC',
																																	'where' => '`active` = 1', 
																																	'limit' => 6));
		$this->view->dates = $this->factory->manufacture('tour_dates', array('associations' => true));
	}

}

?>
