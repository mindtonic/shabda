<?php

$current = current_user();
$object = $this->item;
$posts = $this->posts;

$content = '<div id="userprofile">

'.($current->id == $object->id ? '<p>'.make_link('edit profile','users','update',$object->username).'</p>' : '').'

<h2>'.$object->name().'</h2>
'.$object->image().'
<p>'.$object->location().'</p>
<p>'.$object->profile.'</p>
</div>
';

?>
