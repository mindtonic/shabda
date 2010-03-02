<?php

$content = '

<div id="admin">
	<table class="indexTable" cellspacing="0">
		<tr>
			<th>Date</th>
			<th>Change</th>
			<th>Version</th>
		</tr>
';

if ($this->collection)
	foreach ($this->collection as $object)
	{
		$content .= '
			<tr>
				<td>'.$object->created_at.'</td>
				<td>'.$object->entry.'</td>
				<td>'.$object->version.'</td>
			</tr>
		';
	}


$content .= '
	</table>
</div>

';

?>
