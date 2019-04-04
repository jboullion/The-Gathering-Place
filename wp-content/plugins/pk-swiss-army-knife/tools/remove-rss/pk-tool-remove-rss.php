<?php
	/**
	* DISABLE AUTOMATIC RSS FEED
	*/
	remove_action( 'wp_head', 'feed_links_extra', 3 );
	remove_action( 'wp_head', 'feed_links', 2 );

	add_action('do_feed', 'wpb_disable_feed', 1);
	add_action('do_feed_rdf', 'wpb_disable_feed', 1);
	add_action('do_feed_rss', 'wpb_disable_feed', 1);
	add_action('do_feed_rss2', 'wpb_disable_feed', 1);
	add_action('do_feed_atom', 'wpb_disable_feed', 1);
	add_action('do_feed_rss2_comments', 'wpb_disable_feed', 1);
	add_action('do_feed_atom_comments', 'wpb_disable_feed', 1);
	function wpb_disable_feed() {
		wp_die( __('No feed available,please visit our <a href="'. get_bloginfo('url') .'">homepage</a>!') );
	}

	add_action( 'after_theme_support', 'remove_feed', 100 );
	function remove_feed() {
	   remove_theme_support( 'automatic-feed-links' );
	}