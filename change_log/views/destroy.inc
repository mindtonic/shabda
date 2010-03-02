<?php

$object = $this->item;

$content = '
<div id="form">

<form name="projects" action="?c=change_log&amp;a=destroy&amp;id='.$object->id.'&amp;destroy=true" method="post">

<div class="form">
	<h3>Are you sure you wish to delete this change log entry "'.$object->entry.'"?</h3>
	<br /><br />
	<input type="submit" value="Yes" class="save" /> | <a href="?c=change_log">back</a>
</div>

</form>

</div>
';

?>
