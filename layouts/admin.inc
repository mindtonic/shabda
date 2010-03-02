<?php

$transmenu = new transmenu;

$CONTENT =
'<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
	<meta http-equiv="content-type" content="text/html; charset=windows-1250">
	<meta name="generator" content="Shabda Content Management">
	<title>'.title().' ADMIN</title>
	<link href="stylesheets/admin.css" media="screen" rel="Stylesheet" type="text/css" />
	<link rel="stylesheet" type="text/css" href="javascripts/transmenu/transmenu.css">
	<script language="javascript" src="javascripts/transmenu/transmenu.js"></script>
	'.javascript().'
	'.$this->head.'
	'.$transmenu->head().'
  </head>
  <body onload="init()">
	
	<div id="master">

	<div id="header">		
		<div id="shabdaLogo">'.image('system/logo.png').'</div>
		  <div id="login">
				'.current_datetime().'<br />
				'.login_links().'
			</div>
			<h1>'.SITE_TITLE.' Admin</h1>
 	</div>
 	
 	<div id="menu">'.$transmenu->menu_links($this->active_menu_item).'</div>
 	
 	<table cellspacing="0" id="adminLayoutTable">
	<tr>
	  <td id="leftColumnNavigation" rowspan="2">
    '.admin_menu().'
	  </td>
 	  <td id="adminBreadcrumb">
			<h2>'.make_link(titleize($this->controller),$this->controller,'admin').'</h2>
 	  </td>
	</tr>
	<tr>
	  <td id="content">
			'.($this->report ? '<div class="reports">'.$this->report.'</div>' : '').'

			'.$content.'
	  </td>
	</tr>
 	<tr>
 	  <td colspan="2" id="footer">'.shabda_footer().'</td>
	</tr>
	</table>
	
	</div> <!-- End Master -->

'.$transmenu->render_sub_menus().'

  </body>
</html>
';

?>
