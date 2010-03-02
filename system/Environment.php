<?php

### Include Functions
require_once('system/_functions/system_functions.php');

### Preferences
require_once 'ini.php';

### Test Environment

if (!version_compare(phpversion(), '5.0', '>=')) php5_error();

### Database Connection

@mysql_connect(DB_HOST,DB_USER,DB_PWORD) ? mysql_select_db(DB_NAME) : db_connection_error();

### Autoload classes

function __autoload($classname)
{
	#process classname for entire concepts (blog, forum etc.)
	$parents = preg_split('/_/',$classname);
	$parent = preg_replace('/s$/','',$parents[0]); // provides for singular usage in compound names
	#echo $classname."<br />";
	$directories = array('system','system/_classes',$classname,$parent,$parents[0],'modules');
	foreach ($directories as $directory)
	{
		$filename = $directory."/".$classname.".php";
		if (is_file($filename))
		{
			include_once($filename);
			if (LOG_SYSTEM_OBJECTS_LOADING)
			{
				$observer = ClassLoadingObserver::instance();
				$observer->monitor_class($classname);
			}			
			break;			
		}
	}
}

###

function php5_error()
{
	die("	<img src='images/system/Head.png' style='float:left; padding-right: 10px;' />
				<h1>Installation error:</h1>
				in order to run Shabda you need PHP5.<br />
				Your current PHP version is: ".phpversion().".<br />
				Please contact your system admin or contact Divergent Mind Media for assistance.<br />
				<br />
				<a href='http://divergentmindmedia.com'>Divergent Mind Media</a><br />
				<a href='mailto:questions@divergentmindmedia.com'>questions@divergentmindmedia.com</a><br />
				(828) 398-0730 ");
}

function db_connection_error()
{
	die("	<img src='images/system/Head.png' style='float:left; padding-right: 10px;' />
				<h1>Could Not Connect To Database!</h1>
				Current site mode is <i>".MODE."</i>.
				<br /><br />
				Check ini.php and make sure it is set for your database.");
}

?>
