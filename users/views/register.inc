<?php

$object = $this->item;

$content .= '

<div id="loginForm">

<form name="users" action="?c=users&amp;a=register&amp;id='.$object->id.'" method="post">

<div class="form">
	<h3>username</h3>
	<input type="text" name="users[username]" value="'.$object->username.'" size="40" />
	'.errors_on('username', $object).'
</div>

<div class="form">
	<h3>email</h3>
	<input type="text" name="users[email]" value="'.$object->email.'" size="40" />
	'.errors_on('email', $object).'
</div>

<div class="form">
	<h3>password</h3>
	<input type="password" name="users[password]" value="'.$object->password.'" size="40" />
	'.errors_on('password', $object).'
</div>

<div class="form">
	<h3>confirm password</h3>
	<input type="password" name="users[password_confirmation]" value="'.$object->password_confirmation.'" size="40" />
	'.errors_on('password_confirmation', $object).'		
</div>

<div class="form">		
	<input type="submit" value="register" id="loginSubmit" />			
</div>

</form>

</div>

';

?>
