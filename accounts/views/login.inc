<?php

$user = $this->item;

$content = '
<div id="loginForm">

	<form name="login" action="?c=accounts&amp;a=login" method="post">
	
	<div class="form">
		<h3>username</h3>
		<input type="text" name="accounts[username]" value="'.$user->username.'" size="30">
		'.errors_on('username', $user).'
	</div>
	
	<div class="form">
		<h3>password</h3>
		<input type="password" name="accounts[password]" value="'.$user->password.'" size="30">
		'.errors_on('password', $user).'
	</div>
	
	<div class="form">
		<input type="submit" value="login" id="loginSubmit" />
	</div>
	
	</form>
</div>
';


?>