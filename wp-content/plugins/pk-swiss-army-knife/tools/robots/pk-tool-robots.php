<?php

// should no longer be needed
add_filter('robots_txt', 'pk_robots_txt', 100);
if(!function_exists('pk_robots_txt')) {
	function pk_robots_txt($output) {
		
		// remove /wp-includes/
		$output = preg_replace('/Disallow: \/wp-includes\//', '', $output);
		
		return $output;
	}
}