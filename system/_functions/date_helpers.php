<?php

#
# DATE HELPERS
#

function current_datetime()
{
	return date('l, F jS, Y h:i A');
}

function current_db_datetime()
{
	return date('Y-m-d H:i:s');
}

function database_date($date)
{
	return date('Y-m-d H:i:s', strtotime($date));
}

function date_passed($dbdate)
{
	return (strtotime($dbdate) < time()) ? true : false;
}

function full_date($dbdate)
{
	return date('l, F jS, Y', strtotime($dbdate));
}

function short_date($dbdate)
{
	return date('D, M j, Y', strtotime($dbdate));
}

function display_time($dbtime)
{
	return date('g:ia', strtotime($dbtime));
}

function simple_numeric_date($dbdate)
{
	return date('n/d', strtotime($dbtime));
}

function end_date($start, $duration)
{
	$date_array = getdate(strtotime($start));
	$end_date = mktime(0, 0, 0, $date_array['mon'], $date_array['mday']+($duration -1), $date_array['year']);
	return date('l, F jS, Y',$end_date);
}

function db_end_date($start, $duration)
{
	$date_array = getdate(strtotime($start));
	$end_date = mktime(0, 0, 0, $date_array['mon'], $date_array['mday']+($duration -1), $date_array['year']);
	return date('Y-m-d H:i:s',$end_date);
}

function europe_date($dbdate, $day_of_the_week = true)
{
	$timestamp = strtotime($dbdate);
	$return = '';
	if ($day_of_the_week) $return .= date('l',$timestamp).'<br />';
	$return .= date('j F, Y',$timestamp);
	return $return;
}

function europe_numeric_date($dbdate)
{
	$timestamp = strtotime($dbdate);
	return date('d M, Y',$timestamp);
}

function europe_datetime($dbdate)
{
	$timestamp = strtotime($dbdate);
	return date('d M, Y G:i',$timestamp);
}

?>
