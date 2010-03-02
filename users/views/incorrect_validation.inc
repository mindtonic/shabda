<?php

$object = $this->item;

$content .= '

<h2>Validation was incorrect!</h2>

<p>Please enter your email so that we can resend your validation email.</p>

<div class="contentBox">

<form name="users" action="?c=users&amp;a=resend_validation" method="post">

<div class="form">
	<h3>email</h3>
	<input type="text" name="users[email]" value="'.$object->email.'" size="20" />
	'.errors_on('email', $object).'
</div>

<div class="form">
	<input type="submit" value="save" class="save" />
</div>

</form>

</div>';

?>
