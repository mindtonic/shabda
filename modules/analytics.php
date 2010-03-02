<?php

class analytics
{
	public $analytics_id;
	public $controller;
	public $action;
	public $id;
	public $code_type = 'tracking';
	
	function __construct()
	{
		$this->analytics_id = GOOGLE_ANALYTICS_ID;
		$this->define_location();
	}
	
	function define_location()
	{
		$registry = Registry::instance();
		$this->controller = $registry->controller;
		$this->action = $registry->action;
		$this->id = $registry->id;
	}
	
	function render()
	{
		return $this->embed();
	}
	
	function embed()
	{
		if ($this->code_type == 'urchin')
			return $this->urchin_code();
		else return $this->tracking_code();
	}
	
	function urchin_code()
	{
		return '
<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
</script>
<script type="text/javascript">
_uacct = "'.$this->analytics_id.'";
urchinTracker();
</script>		
		';	
	}
	
	function tracking_code()
	{
		return '
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src=\'" + gaJsHost + "google-analytics.com/ga.js\' type=\'text/javascript\'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
var pageTracker = _gat._getTracker("'.$this->analytics_id.'");
pageTracker._initData();
pageTracker._trackPageview("'.$this->controller.'/'.$this->action.'/'.$this->id.'");
</script>		
		';	
	}

}

?>
