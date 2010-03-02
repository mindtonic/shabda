<?php

$form = new form_builder($this->item);
if ($this->file_upload == true) $form->file_upload = true;
if ($this->wysiwyg == true) $form->wysiwyg = true;
$content = $form->form();

?>
