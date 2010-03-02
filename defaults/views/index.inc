<?php

$content = '

<div id="admin">
	<table class="indexTable" cellspacing="0">
		<tr>
			<th>Thumbnail</th>
			<th>Name</th>
			<th>Client</th>
			<th>Active</th>
			<th>Featured</th>
		</tr>
';

if ($this->collection)
	foreach ($this->collection as $object)
	{
		$content .= '
			<tr>
				<td>'.$object->thumbnail.'</td>
				<td>'.$object->name.'</td>
				<td>'.$object->client.'</td>
				<td>'.on_off_switch('active', $object).'</td>
				<td>'.on_off_switch('featured', $object).'</td>
			</tr>
		';
	}


$content .= '
	</table>
</div>

';

?>
