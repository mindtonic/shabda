<?php

class mp3_player
{
	public $player = "modules/mp3_player/mp3player.swf";
	public $config = "modules/mp3_player/config.xml";
	public $playlist;
	public $width = 300;
	public $height = 300;
	public $name = "mp3player";
	
	#function __construct() {}

	function embed()
	{	
		$output = '
		<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" width="'.$this->width.'" height="'.$this->height.'" id="'.$this->name.'" 
			codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" >
		  <param name="movie" value="'.$this->player.'" />
		  <param name="flashvars" value="config='.$this->config.'&file='.$this->playlist.'" />
		  <embed src="'.$this->player.'" width="'.$this->width.'" height="'.$this->height.'" name="'.$this->name.'"
			flashvars="config='.$this->config.'&file='.$this->playlist.'" 
			type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
		</object>';
		return $output;
	}
}



?>