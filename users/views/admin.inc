<?php

$content = '

<div id="admin">
	<table class="indexTable" cellspacing="0">
		<tr>
			<th>Actions</th>
			<th>Image</th>
			<th>Username</th>
			<th>Email</th>
			<th>Name</th>
			<th>Last Login</th>			
			<th>Active</th>
		</tr>
		<tr>
			<td colspan="7">'.make_link(new_icon(),'users','register').' '.back_admin_link().'</td>
		</tr>
		<tr>
			<td colspan="7" class="paging">
				Pages: '.$this->paginator->numbers("&a=admin").' &nbsp;&nbsp; 
				Letters: '.$this->paginator->letters("&a=admin").'
			</td>
		</tr>
';		

if ($this->collection)
	foreach ($this->collection as $object)
	{
		$content .= '
			<tr>
				<td>'.admin_links($object).(is_super() ? ' '.make_link(roles_icon(), 'users', 'roles', $object->username) : false).'</td>
				<td>'.$object->thumbnail().'&nbsp;</td>
				<td>'.$object->username.'</td>
				<td>'.$object->email.'</td>
				<td>'.$object->name().'</td>
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
