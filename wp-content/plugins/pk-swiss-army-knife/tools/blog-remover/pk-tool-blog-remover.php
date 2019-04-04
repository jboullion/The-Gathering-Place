<?php

/*
Plugin Name: Blog Remover
Description: Enable to remove access to the blog posts in the back end if there is no reason for the client to see them.
Version: 1.0.0
Author: James Boullion
Author URI: https://jboullion.com/
*/

if ( defined( 'ABSPATH' ) && !class_exists( 'PK_Tool_Blog_Remover' ) ) {

	class PK_Tool_Blog_Remover {

		function __construct() {
			add_action( 'admin_menu', array($this,'remove_blog_menu') );
			add_action( 'wp_before_admin_bar_render', array($this,'remove_wp_nodes') );
			add_action( 'init', array($this,'remove_updated_label') );

		}

		function remove_blog_menu(){
			remove_menu_page( 'edit.php' );
		}

		function remove_wp_nodes() {
			global $wp_admin_bar;
			$wp_admin_bar->remove_menu( 'new-post' );
		}

		function remove_updated_label() {
			remove_filter( 'pk_change_post_label', 'pk_return_post_label' );
		}


	}

	new PK_Tool_Blog_Remover;
}