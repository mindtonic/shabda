<?php

$content = '

<div id="admin">
	<table class="indexTable" cellspacing="0">
		<tr>
			<td colspan="7">'.back_admin_link().'</td>
		</tr>
		<tr>
			<th>Actions</th>
			<th>IP</th>
			<th>Time</th>
			<th>Request</th>
			<th>Referer</th>
			<th>Error</th>			
			<th>Agent</th>
		</tr>
		<tr>
			<td colspan="7" class="paging">Pages: '.$this->paginator->numbers("&a=admin").'</td>
		</tr>
';		

if ($this->collection)
	foreach ($this->collection as $object)
	{
		$content .= '
			<tr>
				<td>'.show_link($object).' '.destroy_link($object).'</td>
				<td>'.$object->ip.'&nbsp;</td>
				<td>'.$object->time.'&nbsp;</td>
				<td>'.$object->request.'&nbsp;</td>
				<td>'.$object->referer.'&nbsp;</td>
				<td>'.$object->error.'&nbsp;</td>
				<td>'.$object->agent.'&nbsp;</td>
			</tr>
		';
	}

$content .= '
	</table>
</div>

';

?>