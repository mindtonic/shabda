<?php

$content = '
<h2>Oops, we can\'t validate your account!</h2>
<h3>'.$this->reason_why.'</h3>
<br /><br />
<p>
'.make_link('Click here','users','resend_validation').' to try and resend the validation email.
<br />
Or perhaps you could try to '.make_link('register again','users','register').'
</p>';

?>
