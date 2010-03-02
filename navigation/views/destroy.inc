<?php

$object = $this->item;

$content = '
<div id="form">

<form name="navigation" action="?c=navigation&amp;a=destroy&amp;id='.$object->id.'&amp;destroy=true" method="post">

<div class="form">
	<h3>Are you sure you wish to delete the navigation link 	"'.$object->name.'"?</h3>
	<input type="submit" value="Yes" class="save" /> | <a href="?c=navigation">back</a>
</div>

</form>

</div>
';

?>
