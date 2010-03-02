<?php

require_once('system/_functions/icon_helpers.php');

#
# LINK HELPERS
#

function image($url, $alt = null)
{
	return '<img src="images/'.$url.'"'.($alt ? 'alt="'.$alt.'"' : '').'>';
}

/* API NOTES:
		make_link - if no controller is specified, the function assumes that the
		            description is the same as the controller */

function make_link($description, $controller = null, $action = null, $id = null, $other = null)
{
	if (!$controller) $controller = $description;
	$link = inner_link($controller, $action, $id, $other);
	return '<a href="'.$link.'">'.$description.'</a>';
}

function make_image_link($image, $controller = null, $action = null, $id = null, $other = null)
{
	return '<a href='.inner_link($controller, $action).'>'.image($image).'</a>';
}

function inner_link($controller, $action = null, $id = null, $other = null)
{
	$link = "index.php?c=".$controller;
	if ($action) $link .= "&amp;a=".$action;
	if ($id) $link .= "&amp;id=".$id;
	if ($other) $link .= "&amp;".$other;
	return $link;
}

function link_to($description, $link, $blank = false)
{
	$link = eregi_replace('http://','', $link);
	return '<a href="http://'.$link.'"'.($blank ? ' target="_blank"' : '').'>'.$description.'</a>';
}

function link_to_image($image, $link, $alt = null)
{
	return link_to(image($image, $alt), $link);
}

function admin_links(Model $object)
{
	$delimiter = ' ';

	$links = array();
	$links[] = show_link($object);
	$links[] = edit_link($object);
	$links[] = delete_link($object);
	
	return implode($delimiter, $links);
}

function admin_link(Model $object, $text = false)
{
  return make_link(($text ? $text : admin_icon()), $object->table, 'admin');
}

function back_admin_link()
{
	return make_link(back_icon(), 'admin');
}

function back_link($controller)
{
	return make_link(back_icon(), $controller);
}

function new_link($object, $text = false, $extra = false)
{
	if (is_object($object))	return make_link(($text ? $text : new_icon()), $object->table, 'update');
	else return make_link(($text ? $text : new_icon()), $object, 'update', false, $extra);
}

function edit_link(Model $object, $text = false, $extra = false)
{
  return make_link(($text ? $text : edit_icon()), $object->table, 'update', $object->id, $extra);
}

function show_link(Model $object, $text = false, $extra = false)
{
  return make_link(($text ? $text : show_icon()), $object->table, false, $object->id, $extra);
}

function show_url(Model $object)
{
	return ROOT_PATH.'index.php?c='.$object->table.'&amp;a=show&amp;id='.$object->id;
}

function delete_link(Model $object, $text = false, $extra = false)
{
  return make_link(($text ? $text : destroy_icon()), $object->table, 'destroy', $object->id, $extra);
}

function destroy_link(Model $object, $text = false, $extra = false)
{
  return delete_link($object, $text, $extra);
}

function register_link($text = 'Register')
{
	return make_link($text, 'users', 'register');
}

function on_off($bool)
{
	return $bool ? '<img src="images/system/on.png">' : '<img src="images/system/off.png">';
}

function on_off_switch($link, Model $object)
{
	$image = boolean_icons($object->$link);
	return '<a href="index.php?c='.$object->table.'&amp;a=on_off_switch&amp;s='.$link.'&amp;id='.$object->id.'">'.$image.'</a>';
}

function rss_head_link($controller, $extras = false)
{
	return '<link rel="alternate" type="application/rss+xml" title="'.SITE_TITLE.'" href="index.php?c='.$controller.'&amp;a=syndicate'.$extras.'" />';
}

function rss_link($controller, $image)
{
	return make_image_link($image, $controller, 'syndicate');
}

function user_link(users $user)
{
	return make_link($user->name(), 'users', false, $user->username);
}

function sort_link($table)
{
	return make_link(sort_icon(), $table, 'sort');
}

?>
