<?php

$content = '

<div id="admin">
	<table class="indexTable" cellspacing="0">
		<tr>
			<th>Actions</th>
			<th>Name</th>
			<th>Permission Level</th>
			<th>Active</th>
		</tr>
		<tr>
			<td colspan="4">'.new_link('admin').'  '.back_admin_link().' '.make_link(sort_icon(), 'admin', 'sort').'</td>
		</tr>
';

if ($this->collection)
	foreach ($this->collection as $object)
	{
		$content .= '
			<tr>
				<td>'.admin_links($object).'</td>
				<td>'.$object->name.'</td>
				<td>'.$object->roles->role.'</td>
				<td>'.on_off_switch('active', $object).'</td>
			</tr>
		';
	}


$content .= '
	</table>
</div>

';

?>
