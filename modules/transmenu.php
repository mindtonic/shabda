<?php

# Utilities

function menu_image($name)
{
	return 'images/menu/'.systemize($name).'_over.png';	
}

function menu_image_over($name)
{
	return 'images/menu/'.systemize($name).'.png';	
}

####################

class transmenu
{
	public $parent_menu_items;

	function __construct()
	{
	  $mapper = ModelMapper::instance();
		// Load all root level navigation items
		$navigation = $mapper->get_model('navigation');
		$this->parent_menu_items = $navigation->siblings(true);
	}

	function head()
	{
		$return = '
		<script language="javascript">
			function init() {
				if (TransMenu.isSupported()) {
					TransMenu.initialize();';
		
		$i = 1;	
		foreach ($this->parent_menu_items as $item)
		{
			if ($item->children(true))
			{
				$return .= '
					menu'.$i.'.onactivate = function() { document.getElementById("'.stip_spaces($item->name).'").className = "hover"; };
					menu'.$i.'.ondeactivate = function() { document.getElementById("'.stip_spaces($item->name).'").className = ""; };';
			}
			$i++;	
		}

		$return .= '
				}
			}
		</script>';
		
		return $return;
	}

	function menu_links($active_menu_item = false)
	{
		$return = '';
		foreach ($this->parent_menu_items as $item)
		{
		  if (systemize($item->name) == $active_menu_item)
		  {
		  	if (file_exists(menu_image_over($item->name)))
					$return .= '
						<a href="'.$item->address().'" id="'.stip_spaces($item->name).'">
							<img src="'.menu_image_over($item->name).'" />
						</a>';		  	
		  	else	
		  		$return .= '
						<a href="'.$item->address().'"  id="'.stip_spaces($item->name).'" class="active">'.$item->name.'</a>';	
			}
			else
			{
				if (file_exists(menu_image($item->name)))
				$return .= '
					<a href="'.$item->address().'" id="'.stip_spaces($item->name).'">
						<img src="'.menu_image($item->name).'" class="domroll '.menu_image_over($item->name).'" />
					</a>';
				else
		  		$return .= '
						<a href="'.$item->address().'" id="'.stip_spaces($item->name).'">'.$item->name.'</a>';	
			}
		}
		return $return;
	}
	
	function style()
	{
		$return = '<style type="text/css">';
		foreach ($this->parent_menu_items as $item) $return .= menu_rollover_css($item);
		$return .= '</style>';
		return $return;
	}

	function render_sub_menus()
	{
		$return = '
		<script language="javascript">
		if (TransMenu.isSupported()) {
	
			var ms = new TransMenuSet(TransMenu.direction.down, 1, 0, TransMenu.reference.bottomLeft);';
		
		$i = 1;
		$y = 1;
		foreach ($this->parent_menu_items as $item)
		{
			if ($item->children)
			{
				$return .= '
				var menu'.$i.' = ms.addMenu(document.getElementById("'.stip_spaces($item->name).'"));';
				$x = 0;
				foreach ($item->children(true) as $child)
				{
					$return .= '
					menu'.$i.'.addItem("'.$child->name.'", "'.html_entity_decode($child->address(false)).'");';
					if ($child->children(true))
					{
					  $return .= '
						var submenu'.$y.' = menu'.$i.'.addMenu(menu'.$i.'.items['.$x.']);';
						foreach ($child->children(true) as $grandchild)
						{
							$return .= '
							submenu'.$y.'.addItem("'.$grandchild->name.'", "'.html_entity_decode($grandchild->address(false)).'");';
						}
						$y++;
					}
					$x++;
				}
			}
			$i++;
		}	

		$return .= '
			TransMenu.renderAll();
		}
		</script>		
		';
		
		return $return;
	}
}


?>
