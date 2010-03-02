<?php

$object = $this->item;

$content = '<h1>Showing '.titleize(get_class($object)).'</h1>


<table>
';

foreach($object->table_data as $key => $value)
{
	$content .= '
	<tr>
		<th style="text-align: right; padding: 5px;">'.$key.':</th>
		<td style="padding: 5px;">'.$object->$key.'&nbsp;</td>
	</tr>';
}

$content .= '</table>';


?>