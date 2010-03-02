<?php

$content = '

<div id="admin">
	<table class="indexTable" cellspacing="0">
		<tr>
			<th>Username</th>
			<th>Email</th>
			<th>Name</th>
			<th>Country</th>
			<th>Last Login</th>			
			<th>Active</th>
		</tr>
		<tr>
			<td colspan="6">'.$this->helper->new_link($object).'  <a href="?c=admin">admin</a></td>
		</tr>
';		

if ($this->registry->collection)
	foreach ($this->registry->collection as $object)
	{
		$content .= '
			<tr>
				<td>'.$object->username.'</td>
				<td>'.$object->email.'</td>
				<td>'.$object->name().'</td>
				<td>'.$object->country.'</td>
				<td>'.$object->last_login.'</td>
				<td>'.($object->active ? 'Yes' : 'No').'</td>
			</tr>
		';
	}

$content .= '
	</table>
</div>

';

?>