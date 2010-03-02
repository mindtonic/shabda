<?php

class adSense extends Shabda
{
	public $name = "Google AdSense";
	public $code;
	
	function __construct($code) 
	{
		$this->code($code);
	}

	function embed()
	{	
		return $this->code;
	}
	
	function code($code)
	{
		switch ($code)
		{
			case 1:
				$this->code = '
					<!-- GOOGLE ADSENSE -->
					<script type="text/javascript"><!--
					google_ad_client = "pub-6372243496446865";
					//160x600, created 1/17/08
					google_ad_slot = "1516657742";
					google_ad_width = 160;
					google_ad_height = 600;
					//--></script>
					<script type="text/javascript"
					src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
					</script>	
								
				';
				break;
			case 2:
				$this->code = '
					<script type="text/javascript"><!--
					google_ad_client = "pub-6372243496446865";
					//120x600, created 1/17/08
					google_ad_slot = "4874519758";
					google_ad_width = 120;
					google_ad_height = 600;
					//--></script>
					<script type="text/javascript"
					src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
					</script>
				
				';
				break;
			default:
				$this->code = '
					<!-- GOOGLE ADSENSE -->
				
				';
		}
	}
}


?>