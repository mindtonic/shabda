<?php

$this->head = '
	<script src="javascripts/prototype.js" type="text/javascript"></script>
	<script src="javascripts/scriptaculous.js" type="text/javascript"></script>';

$list = $this->collection;

$content .= '
<h1>Sorting '.titleize($this->controller).'</h1>
'.make_link(back_icon(), $this->controller, 'admin').'

<ol id="sortList">';

foreach ($list as $object)
	$content .= '
	<li id="item_'.$object->id.'">'.$object->name.'</li>';

$content .= '
</ol>

<script type="text/javascript" language="javascript">
	Sortable.create("sortList", {
		onUpdate: function() {
			new Ajax.Request("index.php?c='.$this->controller.'&a=sort", {
			method: "post",
			parameters: { data: Sortable.serialize("sortList") }
			});
		}
	});
</script>';

?>
