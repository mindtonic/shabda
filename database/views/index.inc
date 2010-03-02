<?php

$content = '
<h1>Tables In '.DB_NAME.' / Models In '.SITE_TITLE.'</h1>

<ul>';

if ($this->collection) foreach ($this->collection as $table)
{
	$content .= '
	<li>'.make_link($table,'database',false,$table).'</li>';
}
	
$content .= '</ul>

<h3>'.make_link('Utilities','database','utilities').'</h3>';

?>
