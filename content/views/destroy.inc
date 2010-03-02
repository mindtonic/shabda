<?php

$object = $this->item;

$content = '
<div id="form">

<form name="projects" action="?c=content&amp;a=destroy&amp;id='.$object->id.'&amp;destroy=true" method="post">

<div class="form">
	<h3>Are you sure you wish to delete this content '.$object->handle.'?  This is not recommmended if you\'re not sure what you are doing.  It is very likely that some of your site might break.  If you have questions, contact Divergent Mind Media.</h3>
	<br /><br />
	<input type="submit" value="Yes" class="save" /> | <a href="?c=projects">back</a>
</div>

</form>

</div>
';

?>
