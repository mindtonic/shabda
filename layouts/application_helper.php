<?php


function menu_links()
{
	$links = Factory::Find('navigation', array('where' => '`parent_id` = 0 OR `parent_id` IS NULL'));
	$return = '';
	if ($links) foreach ($links as $link)
	{
		$return .= '
		  <div id="'.stip_spaces($link->name).'" class="menu_button">
			<a href="'.$link->address().'">'.$link->name.'</a>
			</div>
			'.menu_rollover_css($link);
	}
	return $return;
}

function menu_rollover_css(navigation $item)
{
	return '
#'.stip_spaces($item->name).'{background:url(images/menu/'.$item->image_name.');width: 111px; height: 20px;}
#'.stip_spaces($item->name).':hover{background-position: 0px -20px}';
}

?>
