<?php

$user = $this->item;
$roles = $this->roles;

$content = '<h1>Manage Roles for '.$user->username.'</h1>

<table id="databaseDetails" cellspacing="0">';

foreach ($roles as $role)
{
	if ($user->has_role($role->role, false))
	{
		$content .= '
		<tr>
		<td>
			<form name="Roles" action="index.php?c=users&amp;a=remove_role&id='.$user->username.'&role='.$role->id.'" method="post">
			 <input type="submit" value="Revoke Permission" />
			</form>
		</td>
		<th>'.$role->role.'</th>
		<td>&nbsp;</td>
		</tr>';
	}
	else
	{
		$content .= '
		<tr>
		<td>&nbsp;</td>
		<th>'.$role->role.'</th>
		<td>
			<form name="Roles" action="index.php?c=users&amp;a=add_role&id='.$user->username.'&role='.$role->id.'" method="post">
			 <input type="submit" value="Grant Permission" />
			</form>
		</td>
		</tr>';
	}
}

$content .= '</table>';

?>
