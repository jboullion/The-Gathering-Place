<?php

/*
Plugin Name: Comments Remover
Description: Enable to remove access to the comments in the back end if there is no reason for the client to see them.
Version: 1.0.0
Author: Nick Kalscheur
Author URI: https://kalscheur.info/
*/

if ( defined( 'ABSPATH' ) && !class_exists( 'PK_Tool_Comments_Remover' ) ) {

	class PK_Tool_Comments_Remover {	

		public function __construct() {
			add_action( 'admin_menu', array($this,'pk_remove_comment_menus') );
			add_action( 'init', array($this,'pk_remove_comment_support') );
			add_action( 'wp_before_admin_bar_render', array($this,'pk_remove_comments_admin_bar') );
		}

		public function pk_remove_comment_menus(){
			remove_menu_page( 'edit-comments.php' );
		}

		public function pk_remove_comment_support(){
			remove_post_type_support( 'post', 'comments' );
	   	remove_post_type_support( 'page', 'comments' );
		}

		public function pk_remove_comments_admin_bar(){
			global $wp_admin_bar;
	    	$wp_admin_bar->remove_menu('comments');
		}

	}

	new PK_Tool_Comments_Remover;
}