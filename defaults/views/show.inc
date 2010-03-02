<?php

$object = $this->item;

$content = '<h1>Showing '.titleize(get_class($object)).'</h1>


<table>
';

foreach($object->table_data as $key => $value)
{
	$content .= '
	<tr>
		<td>'.$key.'</td>
		<td>'.$object->$key.'</td>
	</tr>';
}

$content .= '</table>';


?>