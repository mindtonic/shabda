<?php 

function navigation_tree()
{
	$return = array();
	$nav = new navigation();

	foreach($nav->siblings() as $sibling)
	{
		$return[] = navigation_item($sibling);
		if ($sibling->children()) $return[] = tree_trace($sibling);
	}

	return '<ul><li>'.implode('</li><li>', $return).'</li></ul>';
}

function tree_trace(navigation $parent)
{
	$return = array();
	foreach($parent->children() as $child)
	{
		$return[] = navigation_item($child);
		if ($child->children()) $return[] = tree_trace($child);
		else continue;				
	}

	return '<ul><li>'.implode('</li><li>', $return).'</li></ul>';
}


function navigation_item(navigation $object)
{
	return '
		<div class="treeName">
			<div class="treeActions">
			'.on_off_switch('active',$object).'
			'.edit_link($object).'
			'.delete_link($object).'
			</div>
			'.$object->name.'
			<div class="treeDescription">'.$object->description.'</div>
		</div>';
}


?>
