<?php

class database_controller extends Controller
{
	protected $credentials = array( "admin" => "super", 
																	"index" => "super",
																	"show" => "super" );

	function admin()
	{
		redirect('database');
	}

	function index()
	{
    $db = new database();
    $data = $db->query("SHOW TABLES FROM ".DB_NAME);
    if (@mysql_num_rows($data))
    {
      $tables = array();
      while ($table = mysql_fetch_array($data)) $tables[] = $table[0];
			$this->view->collection = $tables;
		}
		$this->view->layout = 'admin';
	}

	function show()
	{
    $model = $this->factory->order($this->registry->id);
		$this->view->item = $model;
		$this->view->layout = 'admin';
	}
	
	function utilities()
	{
    $this->view->layout = 'admin';
	}

	###
	
	function add_sticky_to_news()
	{
		if ($this->registry->request('database'))
		{
		  $array = $this->registry->request('database');
		  $db = new database($array['password']);
		  #$sql = "ALTER TABLE `news` ADD `sticky` TINYINT( 1 ) NOT NULL DEFAULT '0' AFTER `description` ;";
			#$db->valid_query($sql);
			admin_redirect('database');
		}
		$this->view->layout = 'admin';
	}
}


?>
