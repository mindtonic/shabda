<?php

$content = '

<div id="admin">
	<table class="indexTable" cellspacing="0">
		<tr>
			<th>Actions</th>
			<th>Role</th>
		</tr>
		<tr>
			<td colspan="2">'.new_link(new roles).'  '.back_admin_link().'</td>
		</tr>
';

if ($this->collection)
	foreach ($this->collection as $object)
	{
		$content .= '
			<tr>
				<td>'.admin_links($object).'</td>
				<td>'.$object->role.'</td>
			</tr>
		';
	}


$content .= '
	</table>
</div>

';

?>
