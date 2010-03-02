<?php

class real_player
{
	public $name = "realPlayer";
	public $file;
	public $width = 300;
	public $height = 300;

	#function __construct() {}

	function embed()
	{	
		$output = '
		<object id="'.$this->name.'" classid="CLSID:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA" height="250" width="540">
		<param name="controls" value="ImageWindow,All">
		<param name="console" value="_master">
		<param name="center" value="true">
		<param name="autostart" value="false">
		<param name="loop" value="false">
		<embed name="'.$this->name.'" src="'.$this->file.'?embed" height="'.$this->height.'" width="'.$this->width.'" nojava="true" controls="ImageWindow,All" console="_master" center="true" autostart="false" pluginspage="http://www.real.com/"></embed>
		</object>';	
		return $output;
	}
}



?>