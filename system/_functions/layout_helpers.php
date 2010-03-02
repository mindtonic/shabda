<?php

#
# LAYOUT HELPERS
#

function javascript()
{
	$return = array();
	$return[] = javascript_link('shabda.js');
	$return[] = javascript_link('prototype.js');
	$return[] = javascript_link('effects.js');
	$return[] = javascript_link('droplicious.js');
	return implode($return);
}

function stylesheets()
{
	$registry = Registry::instance();

	$return = array();
	#$return[] =  stylesheet_link('reset.css');
	$return[] =  stylesheet_link('screen.css');

	if (is_explorer())
		$return[] = stylesheet_link('explorer_screen.css');
/*
	if ($registry->browser == 'firefox')
		$return[] = stylesheet_link('firefox_screen.css');
	elseif ($registry->browser == 'ie6')
		$return[] = stylesheet_link('ie6_screen.css');
	elseif ($registry->browser == 'ie7')
		$return[] = stylesheet_link('ie7_screen.css');
	elseif ($registry->browser == 'safari')
		$return[] = stylesheet_link('safari_screen.css');
	elseif ($registry->browser == 'iPhone')
		$return[] = stylesheet_link('iPhone_screen.css');
*/
	return implode($return);
}

function stylesheet_link($address)
{
	return '
	<link href="stylesheets/'.$address.'" media="screen" rel="Stylesheet" type="text/css" />';
}

function javascript_link($address)
{
	return '
	<script language="javascript" src="javascripts/'.$address.'"></script>';
}

function breadcrumb($trail = array())
{
	$delimiter = " > ";			
	array_unshift($trail, make_link('home'));
	array_filter($trail);
	return implode($delimiter, $trail);
}

function title($trail = array())
{		
	$delimiter = DELIMITER;			
	array_unshift($trail, SITE_TITLE);
	array_filter($trail);
	return strip_tags(implode($delimiter, $trail));
}

#####################
# MENU HELPERS

function menu()
{
	$mapper = ModelMapper::instance();
	// Load all root level navigation items
	$navigation = $mapper->get_model('navigation');
	$menu = $navigation->siblings(true);

	// Assemble output
	$output = array();
	if ($menu) foreach ($menu as $link)
	{
		if (current_selection($link))
			$output[] = '
			<a href="'.$link->address().'" class="on" '.tooltip_script($link->description).'>'.$link->name.'</a>';
		else $output[] = '
			<a href="'.$link->address().'" '.tooltip_script($link->description).'>'.$link->name.'</a>';
	}
	return implode('',$output);
}

function current_selection(navigation $navigation)
{
	#return strpos($navigation->address(), $_SERVER['QUERY_STRING']) ? true : false;
	return false;
}


function submenu()
{
	$registry = Registry::instance();
	if ($registry->section)
	{
		// Load section
		$section = new navigation;
		$section->load_by_name($registry->section);

		// If section exists and has children
		if ($section->id && $section->children(true))
		{
			// Loop through subsections
			$output = array();
			foreach ($section->children as $link)
			{
				if ($registry->subsection == $link->name)
					$output[] = '<a href="'.$link->address().'" class="on" '.tooltip_script($link->description).'>'.$link->name.'</a>';
				else $output[] = '<a href="'.$link->address().'" '.tooltip_script($link->description).'>'.$link->name.'</a>';
			}
			return implode('',$output);
		}
	}
}

/* need to install permissions check against the current user's roles */
function admin_menu()
{
	// Load all admin navigation objects
	$mapper = ModelMapper::instance();
	$admin = $mapper->get_model('admin');
	$navigation = $admin->load_active_admin();
	// Instate User
	$user = current_user();
	// Loop, check and assign
	$output = array();
	$output[] = admin_menu_piece(make_link('Home','home'));
	$output[] = admin_menu_piece(make_link('Admin', 'admin'));
	foreach ($navigation as $link)
	{
		if ($link->roles->role == "super" && $user->super == 1)
			$output[] = admin_menu_piece(make_link(titleize($link->name), $link->controller(), 'admin'));
		elseif ($link->roles->role != "super" && $user->has_role($link->roles->role))
		  $output[] = admin_menu_piece(make_link(titleize($link->name), $link->controller(), 'admin'));
	}



	return "<ul>".implode('
	',$output)."</ul>";
}

function admin_menu_piece($link)
{
	return "<li>".$link."</li>";
}

#####################

function preload_images($directory)
{
	$writer = new Writer;
	$files = $writer->read_directory($directory);
	if ($files['errors']) SHABDA::shabda_error($files['errors']);
	else return "'".implode("','",$files)."'";
}

#####################

function login_form($registry)
{
	if ($registry->is_logged_in())
	{
		$return .= 'Welcome '.$registry->user->username.'<br />';
		if ($registry->is_super()) $return .= make_link('admin').DELIMITER;
		$return .= make_link('logout','accounts','logout').'<br /><br />';
	}
	else
	{
		$return .= '
			<form name="login" action="index.php?c=accounts&amp;a=login" method="post">
				<input type="submit" value="&nbsp;" class="submit" style="background: white;" />
				<input type="text" name="accounts[username]" value="" size="3" />
				<input type="password" name="accounts[password]" value="" size="3" />
			</form>';
	}
	return $return;
}

function login_links()
{
	$registry = Registry::instance();

	$return = array();
	if ($registry->is_logged_in())
	{
		$return[] = strtolower(greeting()).' '.user_link($registry->user);
		if ($registry->is_admin()) $return[] = make_link('admin');
		$return[] = make_link('logout','accounts','logout');
	}
	else
	{
		$return[] = make_link('login','accounts','login');
		if (ALLOW_REGISTRATION) $return[] = make_link('register','users','register');
	}
	return implode($return, DELIMITER);
}

#####################

function partial($name, $folder = "layouts")
{
	$filename = $folder.'/partials/'.$name.'.inc';
	if (file_exists($filename))
		return file_get_contents($filename);
}

function mindtonic_footer()
{
	return 'composed by mindtonic';
}

function shabda_footer()
{
	return 'powered by shabda '.VERSION.DELIMITER.'composed by <a href="http://mindtonic.net" target="_blank">mindtonic</a>';
}

function greeting()
{
	#$greetings = array('Welcome', 'Aloha', 'Greetings', 'Kon Ni chi Wa', 'Hola', 'Great to see you',
	#								 		'Bonjour', 'Howdy', 'Was\' Happenin\'');
	#return $greetings[array_rand($greetings)];
	return "welcome";
}

?>
