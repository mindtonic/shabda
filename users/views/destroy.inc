<?php

$object = $this->item;

$content = '
<div id="form">

<form name="users" action="?c=users&amp;a=destroy&amp;id='.$object->id.'&amp;destroy=true" method="post">

<div class="form">
	<h3>Are you sure you wish to delete '.$object->name().'?</h3>
	<input type="submit" value="Yes" class="save" /> | <a href="?c=users">back</a>
</div>

</form>

</div>
';

?>