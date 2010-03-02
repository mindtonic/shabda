<?php

$object = $this->item;

$content = '
<h1>Details About '.$object->table.'</h1>

<table id="databaseDetails" cellspacing=0>
	<tr>
	  <th>Field</th>
	  <th>Type</th>
	  <th>Default</th>
	  <th>Required</th>
	  <th>Limit</th>
	  <th>Unique</th>
	</tr>';
	
foreach ($object->table_data as $field => $type)
{
	$content .= '
	<tr>
	  <td>'.$field.'&nbsp;</td>
	  <td>'.$type.'&nbsp;</td>
	  <td>'.$object->defaults[$field].'&nbsp;</td>
	  <td class="star">'.($object->is_required($field) ? '*' : '').'&nbsp;</td>
	  <td>'.($object->has_limit($field) ? $object->has_limit($field) : '').'&nbsp;</td>
	  <td class="star">'.($object->is_unique($field) ? '*' : '').'&nbsp;</td>
	</tr>';
}

$content .= '
</table>';

?>
