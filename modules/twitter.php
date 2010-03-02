<?php

class twitter
{
	public $twitter_name;

	function __construct()
	{
		$this->twitter_name = TWITTER_NAME;
	}

	function embed()
	{
		return '
			<div id="twitter">
				<div id="twitter-image">
					<a href="http://twitter.com/'.$this->twitter_name.'" target="_blank">
						<img src="images/twitter.png" />
					</a>
				</div>
				<ul id="twitter_update_list"></ul>
				<a href="http://twitter.com/'.$this->twitter_name.'">follow me on Twitter</a>
			</div>		
		';	
	}

	function footer()
	{
		return '
<script type="text/javascript" src="http://twitter.com/javascripts/blogger.js"></script>
<script type="text/javascript" src="http://twitter.com/statuses/user_timeline/'.$this->twitter_name.'.json?callback=twitterCallback2&amp;count=10"></script>		
		';
	}

}

?>