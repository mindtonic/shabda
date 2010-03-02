<?php

$user = $this->registry->user();

$content .= '

<h1>System Error</h1>
<h2>Our apologies. We have been notified and will investigate.</h2>

<br /><br />

<div id="landingPage">

'.($this->registry->error_message ? $this->registry->error_message : "").'
	

</div>';

?>