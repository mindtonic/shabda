<?php

$menu = new transmenu;
$analytics = new analytics;
$domroll = new domroll;

$CONTENT = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<meta name="generator" content="Shabda Content Management">
	<title>'.title().'</title>
	'.stylesheets().'
	'.javascript().'
	<link rel="stylesheet" type="text/css" href="javascripts/transmenu/transmenu.css">
	<script language="javascript" src="javascripts/transmenu/transmenu.js"></script>
	'.$this->head.'
	'.$menu->head().'
  </head>
  <body onload="init()">
	
	<div id="master">
	
	<div id="header">
	
			<div id="login">'.login_links().'</div>
 		
  		<div id="logo"><h1>'.SITE_TITLE.'</h1></div> 
 		
 			<div id="menu">'.$menu->menu_links($this->active_menu_item).'</div>
 		
 	</div>
 		
 		<div id="content">'.$content.'</div>
 		
 		<div id="footer">'.shabda_footer().'</div>
	
	</div>

'.$domroll->render().'
'.$menu->render_sub_menus().'

  </body>
</html>
';

?>
