<?php

$item = $this->item;
$links = $this->links;

$form = new form_builder($item);
$form->add_ignore_field('parent_id');

$content = $form->start_form();

$content .= '

	<div class="form">
		'.$form->label('Menu Position').'
		<select name="navigation[parent_id]">
		<option value="">Main Menu</option>
		<optgroup label="SubMenu Of:">';
		
	if ($links)
		foreach ($links as $link)
			$content .= '<option value="'.$link->id.'"'.($item->parent_id == $link->id ? ' selected="1"' : '').'>'.$link->name.'</option>';
		
	$content .= '
		</optgroup>
		</select>
		'.errors_on('parent_id', $item).'
	</div>';

$content .= $form->fields();
$content .= $form->submit_button();
$content .= $form->end_form();

?>
