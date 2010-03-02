<?php

$nav = $this->item;

$content = '

<div id="navigationAdmin">

<h2>Site Navigation Tree</h2>

<p>'.new_link('navigation').'</p>

<ul>
';

if ($nav)
{
	foreach($nav->siblings() as $sibling)
	{
		$content .= navigation_item($sibling, $this->helper);
		if ($sibling->children())
		{
			$content .= '<ul>';
			foreach($sibling->children() as $child)
			{
				$content .= navigation_item($child, $this->helper);
				if ($child->children())
				{
					$content .= '<ul>';
					foreach($child->children() as $child)
						$content .= navigation_item($child, $this->helper);	
					$content .= '</ul>';
				}									
			}
			$content .= '</ul>';
		}
	}
}
	
$content .= '
	</ul>
</div>';

#$nav->diagnostics();
?>

