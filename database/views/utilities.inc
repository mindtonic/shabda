<?php

$content = '

<h1>Database Functions</h1>';

$db = new database;
if ($db->is_locked())
{
	$content .= '
	<ul>
	<li>'.make_link('Add Sticky To News','database','add_sticky_to_news').'</li>
	</ul>';
}
else $content .= 'DATABASE IS LOCKED';

?>
