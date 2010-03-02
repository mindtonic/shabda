<?php

$section = $this->item;

$content .= '
<div id="landingPage">

'.landing_image($section->name).'

<table>
	<tr>';
	
if ($section->children(true))
{
	foreach ($section->children as $section)
	{
		$content .= '					
			<td class="landingLink">
								
				<div class="linkHeader">
					<a href="'.$section->address().'">'.$section->name.'</a>
				</div>
				
				<div class="linkDescription">
					<a href="'.$section->address().'">'.$section->description.'</a>
				</div>
				
			</td>
		';
	}
}

$content .= '
	</tr>
</table>

</div>';

?>
