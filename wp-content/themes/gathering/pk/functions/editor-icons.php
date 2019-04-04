<?php

// stop if not in admin
if(!is_admin()) { return; }
/*
// editor hooks	
add_action('admin_enqueue_scripts', 'pk_editor_icons_scripts');
//add_filter('mce_css', 'pk_editor_icons_css');
add_filter('mce_external_plugins', 'pk_editor_icons_plugin');
add_filter('mce_buttons', 'pk_editor_icons_button');

// load scripts
function pk_editor_icons_scripts() {
	//wp_enqueue_style('font-awesome','https://use.fontawesome.com/releases/v5.0.7/css/all.css');
	//wp_enqueue_style('pk-editor-icons', get_template_directory_uri().'/pk/misc/pk-editor-icons.css');
	wp_enqueue_style('pk-insert-video', get_template_directory_uri().'/pk/misc/pk-insert-video.css');
	wp_enqueue_style('pk-button', get_template_directory_uri().'/pk/misc/pk-button.css');
}

// in-editor css

function pk_editor_icons_css($css) {
	if(!empty($css)) { $css .= ','; }
	//$css .= get_template_directory_uri().'/pk/lib/font-awesome/css/font-awesome.min.css';
	$css .= 'https://use.fontawesome.com/releases/v5.0.7/css/all.css';
	return $css;
}


// editor plugin
function pk_editor_icons_plugin($plugins) {
	//$plugins['pk_icon'] = get_template_directory_uri().'/pk/misc/pk-editor-icons.js';
	$plugins['pkInsertVideo'] = get_template_directory_uri().'/pk/misc/pk-insert-video.js';
	$plugins['pkButton'] = get_template_directory_uri().'/pk/misc/pk-button.js';
	return $plugins;
}

// editor button
function pk_editor_icons_button($buttons) {
	//$buttons[] = 'pk_icon_list';
	$buttons[] = 'pkInsertVideo';
	$buttons[] = 'pkButtonBTN';
	return $buttons;
}
*/