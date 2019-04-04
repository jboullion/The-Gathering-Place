<?php

/*
Plugin Name: Disable Admin Change Email Notification
Description: Prevents the email confirmation from going out when the admin email is changed so it can actually be changed to a non admin account. Add "add_filter( 'pk_tool_disable_admin_email_not', '__return_false' );" to functions.php to disable on a site by site basis.
Version: 1.0.0
Author: Nick Kalscheur
Author URI: https://kalscheur.info/
*/

/*
*  Courtesy of https://premium.wpmudev.org/forums/topic/how-do-i-change-the-admin-email-from-without-email-confirmation#post-843453
*/

if ( defined( 'ABSPATH' ) && !class_exists( 'PK_Tool_Disable_Admin_Email_Notification' ) ) {

	class PK_Tool_Disable_Admin_Email_Notification {	

		public function __construct() {
			//Add out actions on the init action
			add_action('init',array($this,'pk_init'));
		}

		public function pk_init(){
			//Allow a filter to disable this on a site by site basis
			$run = apply_filters( 'pk_tool_disable_admin_email_not', true);

			//Only run in admin and if it hasn't been overwrited
			if($run && is_admin()){ 
				//Remove the default actions when a new admin email is added
				remove_action( 'add_option_new_admin_email', 'update_option_new_admin_email' );
				remove_action( 'update_option_new_admin_email', 'update_option_new_admin_email' );

				//Add our own actions for when the admin email is changed
				add_action( 'add_option_new_admin_email', array($this,'pk_update_option_new_admin_email'), 10, 2 );
				add_action( 'update_option_new_admin_email', array($this,'pk_update_option_new_admin_email'), 10, 2 );
			}
		}

		/**
		 * Disable the confirmation notices when an administrator
		 * changes their email address.
		 *
		 * @see http://codex.wordpress.com/Function_Reference/update_option_new_admin_email
		*/
		public function pk_update_option_new_admin_email( $old_value, $value ) {
		   update_option( 'admin_email', $value );
		}

	}

	new PK_Tool_Disable_Admin_Email_Notification;
}