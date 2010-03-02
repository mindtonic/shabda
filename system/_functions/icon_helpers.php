<?php

# ICON HELPERS

function back_icon() {
	return image('icons/001_23.png', 'back');
}

function new_icon() {
	return image('icons/001_01.png', 'new');
}

function edit_icon() {
	return image('icons/001_45.png', 'edit');
}

function show_icon() {
	return image('icons/001_38.png', 'show');
}

function destroy_icon() {
	return image('icons/001_49.png', 'destroy');
}

function validate_icon() {
	return image('icons/001_39.png', 'validate');
}

function boolean_icons($value) {
	return $value ? true_icon() : false_icon();
}

function true_icon() {
	return image('icons/001_06.png', 'true');
}

function false_icon() {
	return image('icons/001_05.png', 'false');
}

function roles_icon() {
	return image('icons/001_54.png', 'roles');
}

function activate_icon() {
	return image('icons/001_01.png', 'activate');
}

function deactivate_icon() {
	return image('icons/001_02.png', 'deactivate');
}

function rss_icon() {
	return image('icons/001_31.png', 'rss');
}

function feeds_icon() {
	return rss_icon();
}

function items_icon() {
	return image('icons/001_36.png', 'items');
}

function thumbs_up_icon() {
	return image('icons/001_18.png', 'thumbs up');
}

function thumbs_down_icon() {
	return image('icons/001_19.png', 'thumbs down');
}

function heart_icon() {
	return image('icons/001_14.png', 'heart');
}

function sort_icon() {
	return image('icons/sort_icon.gif', 'sort');
} # image('icons/001_44.png', 'images')

function folder_icon() {
	return image('icons/001_43.png', 'folder');
}

function admin_icon() {
	return folder_icon();
}

function images_icon() {
	return image('icons/Images.png', 'images');
}


?>
