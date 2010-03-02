<?php

$registry = Registry::instance();
$user = $registry->user();

$content .= '

<h1>Welcome '.$user->username.'!</h1>

';


?>
