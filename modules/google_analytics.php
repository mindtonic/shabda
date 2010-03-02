<?php

class google_analytics
{
	public $google_id = GOOGLE_ANALYTICS_ID;
	private $track;
	
	function __construct()
	{
		$registry = Registry::instance();
		$this->track = $registry->controller.'/'.$registry->action.($registry->id ? '/'.$registry->id : '');
	}
	
	function embed()
	{
		return $this->analytics();
	}
	
	function analytics()
	{
		return '
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src=\'" + gaJsHost + "google-analytics.com/ga.js\' type=\'text/javascript\'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
var pageTracker = _gat._getTracker("'.$this->google_id.'");
pageTracker._initData();
pageTracker._trackPageview("'.$this->track.'");
</script>	
		';
	}
	
	function legacy_analytics()
	{
		return '
<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
</script>
<script type="text/javascript">
_uacct = "'.$this->google_id.'";
urchinTracker();
</script>	
		';
	}
}


?>
