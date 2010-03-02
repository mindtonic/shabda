<?php

$content = '
<table cellspacing="0" id="adminIndexTable">
	<tr>
		<td id="siteChanges">

<h2>Recent Site Changes</h2>
<ul id="recentChanges">';

if ($this->changeLog) foreach ($this->changeLog as $log)
{
	$content .= '
	<li>
	'.$log->entry.' ('.full_date($log->created_at).')
	</li>';
}

$content .= '
</ul>

		</td>
		<td id="recentUsers">
		
<h2>Recent Users</h2>		
<ul>';
		
if ($this->recentUsers) foreach ($this->recentUsers as $user)
{
	$content .= '	
	<li>'.$user->name().' ('.$user->last_login.')</li>	';
}

$content .= '
</ul>

		</td>
	</tr>
</table>
';
?>
