<?php
	
	// if acf isn't enabled, get out
	if(!class_exists('acf')) { return; }

	foreach(glob(dirname(__FILE__).'/acf/*.php') as $filepath) { include_once $filepath; }

	// change where acf json files are saved
	add_filter('acf/settings/save_json', 'pk_acf_save_json');
	function pk_acf_save_json($path) { return get_stylesheet_directory().'/includes/acf-json'; }

	// add our custom folder to places acf json are loaded from
	add_filter('acf/settings/load_json', 'pk_acf_load_json');
	function pk_acf_load_json($paths) { return array_merge($paths, array(get_stylesheet_directory().'/includes/acf-json')); }

	// acf options pages
	if(function_exists('acf_add_options_page')) {
		acf_add_options_sub_page(array('title'=>'Website Options', 'parent'=>'themes.php', 'capability'=>'edit_theme_options'));
	}