<?php

$object = $this->item;
$content = '<h2>'.$object->name().DELIMITER.'Edit Profile</h2><br /><br />';

$form = new form_builder($this->item);
$form->id = $object->username;
$form->file_upload = true;
$content .= $form->form();

?>