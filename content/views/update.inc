<?php

$form = new form_builder($this->item);
$form->rows = 400;
$form->cols = 600;
$form->wysiwyg = true;
$form->toolset = 'DMM';
$content = $form->form();


?>