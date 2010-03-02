<?php

$content = '

<div id="admin">
	<table class="indexTable" cellspacing="0">
		<tr>
			<th>Actions</th>
			<th>id</th>
			<th>Handle</th>
			<th>Content</th>
		</tr>
		<tr>
			<td colspan="4">'.new_link(new content).' '.back_admin_link().'</td>
		</tr>
';

if ($this->collection)
	foreach ($this->collection as $object)
	{
		$content .= '
			<tr>
				<td>'.edit_link($object).'</td>
				<td>'.$object->id.'</td>
				<td>'.$object->handle.'</td>
				<td>'.$object->content.'</td>
			</tr>
		';
	}


$content .= '
	</table>
</div>

';

?>
