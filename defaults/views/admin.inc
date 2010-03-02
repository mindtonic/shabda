<?php

$content = '

<div id="admin">
	<table class="indexTable" cellspacing="0">
		<tr>
			<th>Actions</th>
			<th>Thumbnail</th>
			<th>Name</th>
			<th>Client</th>
			<th>Active</th>
			<th>Featured</th>
		</tr>
		<tr>
			<td colspan="6">'.new_link('*default*').'  '.back_admin_link().'</td>
		</tr>
';

if ($this->collection)
	foreach ($this->collection as $object)
	{
		$content .= '
			<tr>
				<td>'.admin_links($object).'</td>
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
