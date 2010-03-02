<?php

$content = '

<div id="contactForm">';

$form = new form_builder($this->item);
$form->captcha = true;
$content .= $form->form();

$content .= '

</div>';


?>
