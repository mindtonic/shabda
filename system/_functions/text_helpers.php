<?php

#
# TEXT HELPERS
#

function humanize($string)
{
	return ereg_replace('_', ' ', $string);
}

function systemize($string)
{
	return ereg_replace(' ', '_', strtolower($string));
}

function methodize($string)
{
	return systemize($string);
}

function stip_spaces($string)
{
	return ereg_replace(' ', '', $string);
}

function titleize($string)
{
	$string = humanize($string);
	return ucwords($string);
}

function tooltip($message, $text = null, $link = null)
{
	return '<a href="'.($link ? $link : '').'" '.tooltip_script($message).'>'.$text.'</a>';
}

function tooltip_script($message)
{
	if ($message)
		return 'onmouseover="Tip(\''.addslashes($message).'\')"';
}
 
function simple_text($text)
{
	$text = "<p>".ereg_replace('<p></p>','',preg_replace('/\r(\n)?\r(\n)?/','</p><p>',$text))."</p>";
	$text = preg_replace('/\r(\n)?/', '<br />', $text);
	return $text;
}

function simple_link($link)
{
	$link = 'http://'.preg_replace('/http:\/\//','',$link);
	return '<a href="'.$link.'" target="_blank">'.$link.'</a>';
}

function format_link($link)
{
	return preg_replace(array('/&amp;/','/&/'), array('&','&amp;'), $link);
}

function limit_text($text, $limit = 100)
{
	if (strlen($text) > $limit)
	{
		$text_array = preg_split('/\s/', $text);
		$text = "";
		foreach ($text_array as $word)
		{
			if (strlen($text." ".$word) < $limit) $text .= " ".$word;
			else
			{
				$text .= "...";
				break;
			}
		}
	}
	return $text;
}

?>
