<?php

$form = new form_builder($this->item);
$form->relationships = array('role_id' => 'roles');
$content .= $form->form();

?>
