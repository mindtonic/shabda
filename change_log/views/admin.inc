<?php

$content = '

<div id="admin">
	<table class="indexTable" cellspacing="0">
		<tr>
			<th>Actions</th>
			<th>Date</th>
			<th>Entry</th>
			<th>Version</th>
		</tr>
		<tr>
			<td colspan="4">'.new_link('change_log').'  '.back_admin_link().'</td>
		</tr>
';

if ($this->collection)
	foreach ($this->collection as $object)
	{
		$content .= '
			<tr>
				<td>'.admin_links($object).'</td>
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
