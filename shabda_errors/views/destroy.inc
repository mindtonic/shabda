<?php

$object = $this->item;

$content = '
<div id="form">

<form name="projects" action="?c=shabda_errors&amp;a=destroy&amp;id='.$object->id.'&amp;destroy=true" method="post">

<div class="form">
	<h3>Are you sure you wish to delete this error?</h3>
	<br /><br />
	<input type="submit" value="Yes" class="save" /> | <a href="?c=shabda_errors&amp;a=admin">back</a>
</div>

</form>

</div>
';

?>
