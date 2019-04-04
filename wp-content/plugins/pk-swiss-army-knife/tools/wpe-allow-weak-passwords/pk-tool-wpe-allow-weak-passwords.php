<?php
/*
Plugin Name: WPE Do Not Force Strong Passwords
Description: Deactivate Force Strong Passwords
Version: 0.1
Author: WP Engine
Author URI: http://wpengine.com/
License: GPLv2
*/

add_action('init', function() {
	remove_action( 'admin_enqueue_scripts', 'slt_fsp_enqueue_force_zxcvbn_script' );
	remove_action( 'login_enqueue_scripts', 'slt_fsp_enqueue_force_zxcvbn_script' );
	remove_action( 'user_profile_update_errors', 'slt_fsp_validate_profile_update', 0, 3 );
	remove_action( 'validate_password_reset', 'slt_fsp_validate_strong_password', 10, 2 );

});