<?php

/*
Plugin Name: Password Protect
Description: Enable the password protection on a page to actually protect that page.
Version: 1.0.0
Author: James Boullion
Author URI: https://jboullion.com/
*/

if ( defined( 'ABSPATH' ) && !class_exists( 'PK_Password_Protect' ) ) {

	class PK_Password_Protect {

		function __construct() {
			add_action( 'wp', array($this,'password_protect') );
			add_filter( 'post_password_expires', array($this,'password_cookie_expiry') );
		}

		function password_cookie_expiry( $expires ) {
			return time() + 1 * DAY_IN_SECONDS;
		}


		function password_protect(){
			global $post;

			if( post_password_required( $post ) ){
?>
					<style>
						* { box-sizing: border-box; }

						body { text-align: center; }

						form.post-password-form { display: inline-block; text-align: left; }
						form.post-password-form input[type="password"] { border: 1px solid #ddd; padding: 10px; }
						form.post-password-form input[type="submit"] { background-color: #ffffff; border: 1px solid #ddd; cursor: pointer; padding: 10px 20px; }

						form.post-password-form input[type="submit"]:hover { background-color: #f1f1f1; }
						form.post-password-form label { font-weight: bold; font-size: 20px; }
					</style>
<?php 
				echo get_the_password_form();

				die();

			}

		}


	}

	new PK_Password_Protect;
}
