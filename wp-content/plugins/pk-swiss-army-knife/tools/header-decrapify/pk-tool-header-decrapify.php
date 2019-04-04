<?php
	/**
	* CLEAN UP THE WP HEAD
	*/
	remove_action( 'wp_head', 'wp_generator');
	remove_action( 'wp_head', 'wp_shortlink_wp_head');
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links');
	remove_action( 'wp_head', 'wp_oembed_add_host_js');
	remove_action( 'wp_head', 'rest_output_link_wp_head');
	remove_action( 'wp_head', 'rsd_link');
	remove_action( 'wp_head', 'wlwmanifest_link');
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' ); 
	remove_action( 'rest_api_init', 'wp_oembed_register_route');
	remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10);
	add_filter( 'emoji_svg_url', '__return_false' );

	//https://www.bybe.net/yoast-seo-guide-disable-schema-json-ld-wordpress/
	add_filter('wpseo_json_ld_output', 'bybe_remove_yoast_json', 10, 1);
	function bybe_remove_yoast_json($data){
		$data = array();
		return $data;
	}