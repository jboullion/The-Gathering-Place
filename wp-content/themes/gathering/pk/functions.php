<?php
	
	// easy logout (doesn't require new page creation)
	if($_SERVER['REQUEST_URI'] == '/logout/') {
	
		// default redirect url
		$url = '/login/';
		
		// if WooCommerce, attempt to find "My Account" page
		if(class_exists('WooCommerce')) {
			$url = pk_get_link(get_option('woocommerce_myaccount_page_id'));
		}
		
		// logout & redirect user
		wp_logout();
		wp_redirect($url);
		exit();
	}
	
	// always add menu support
	add_theme_support('menus');
	
	// always include common.php first
	include_once TEMPLATEPATH.'/pk/functions/common.php';
	
	// include all the function files except common.php
	foreach(preg_grep('/common\.php$/', glob(TEMPLATEPATH.'/pk/functions/*.php'), PREG_GREP_INVERT) as $filepath) { include_once $filepath; }