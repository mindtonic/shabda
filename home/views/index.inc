<?php

$news = $this->news;
$dates = $this->dates;

$content = '
<table id="homePageTable" cellspacing="0" width="100%">
	<tr>
		<td id="homePageNews">
		<h1>News</h1>
';

if ($news) foreach ($news as $item)
{
	$content .= '
			<div class="homePageNewsItem">
			<h2>'.$item->title.'</h2>
			'.$item->description.'
			</div>
';
}

$content .= '
		'.make_link('More News...','news').'
		</td>

		<td id="homePageTourDates">
		<h1>Upcoming Shows</h1>
';


if ($dates) foreach ($dates as $date)
{
	$content .= '
			<div class="homePageTour">
			<div class="homePageTourDate">'.full_date($date->date).'</div>
			<div class="homePageTourVenue">'.$date->tour_venues->name.'</div>
			<div class="homePageTourVenueLocation">'.$date->tour_venues->location().'</div>
			'.($date->description ? '<div class="homePageTourDescription">'.$date->description.'</div>' : '').'
			</div>
';
}

$content .= '
		</td>
	</tr>
</table>
';


?>
