<?php

$content .= '
<h1>Contact Us</h1>';

$form = new form_builder($this->item);
$content .= $form->form();

?>
