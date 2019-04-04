<?php

/*
Plugin Name: Powderkeg Swiss Army Knife
Plugin URI:  https://powderkegwebdesign.com
Description: Useful and toggleable utilities for Powderkeg Sites
Version:     0.4.4
Author:      Nick Kalscheur
Author URI:  http://kalscheur.info
Text Domain: powderkeg-swiss-army-knife
License:     GPL-2.0+

Disclaimer:  This plugin is mostly a collection of resources and smaller projects
             that work to enhance the experience of Powderkeg websites. We do not
             take credit for all of the individual pieces of functionality.
*/

// auto-update functionality
require('plugin-update-checker/plugin-update-checker.php');
$updateChecker = PucFactory::buildUpdateChecker('https://www.powderkegwebdesign.com/wp-updates/?action=get_metadata&slug=pk-swiss-army-knife', __FILE__);

// semd along a "secret" key to the updater
$updateChecker->addQueryArgFilter('pk_swiss_army_knife_update_checks');
function pk_swiss_army_knife_update_checks($args) {
	$args['secret_key'] = 'IGlTHgCwpwWbDYsV7xYeys2VvsWhSdXb';
	return $args;
}

if ( defined( 'ABSPATH' ) && ! class_exists( 'Powderkeg_Swiss_Army_Knife' ) ) {

	define( '__PKSAK_USR_FILE__', __FILE__ );
	define( '__PKSAK_USR_DIR__', __DIR__ );
	define( '__PKSAK_TOOLS__', 'pk-swiss-army-knife/tools' );

	class Powderkeg_Swiss_Army_Knife {		

		/**
		* Plugin version.
		*
		* @var string
		*/
		const VERSION = '0.4.3';

		/**
		* Instance of this class.
		*
		* @var object
		*/
		protected static $instance = null;

		/**
		* Initialize the plugin.
		*/
		private function __construct() {

			// Activate plugin when new blog is added.
			add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );

			$this->load_tools();

			/**
			 * Admin actions.
			 */
			if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {
				$this->admin_include();
				add_action( 'admin_init', array( $this, 'update' ) );
			}

		}

		/**
		* Admin includes
		*
		* @return void
		*/
		private function admin_include() {
			require_once 'includes/class-pk-sak-admin.php';
		}

		/**
		* Return an instance of this class.
		*
		* @return object A single instance of this class.
		*/
		public static function get_instance() {
			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}

			return self::$instance;
		}

		/**
		* Fired when the plugin is activated.
		*
		* @param  boolean $network_wide True if WPMU superadmin uses
		*                               "Network Activate" action, false if
		*                               WPMU is disabled or plugin is
		*                               activated on an individual blog.
		*
		* @return void
		*/
		public static function activate( $network_wide ) {
			if ( function_exists( 'is_multisite' ) && is_multisite() ) {
				if ( $network_wide  ) {

					// Get all blog ids
					$blog_ids = self::get_blog_ids();

					foreach ( $blog_ids as $blog_id ) {
						switch_to_blog( $blog_id );
						self::single_activate();
					}

					restore_current_blog();
				} else {
					self::single_activate();
				}
			} else {
				self::single_activate();
			}
		}

		/**
		* Fired for each blog when the plugin is activated.
		*
		* @return   void
		*/
		private static function single_activate() {
			if ( ! current_user_can( 'activate_plugins' ) ) {
				return;
			}

			$plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';
			check_admin_referer( 'activate-plugin_' . $plugin );

			//add_option( 'pk_sak', $options );
			add_option( 'pk_sak_version', self::VERSION );
		}

		/**
		* Update the plugin options.
		*
		* @return   void
		*/
		public function update() {
			$version = get_option( 'pk_sak_version' );
			if ($version <> self::VERSION) {
				update_option( 'pk_sak_version', self::VERSION );
			}
		}

		/**
		* Load all of the selected tools
		*
		* @return   void
		*/
		private function load_tools(){
			$options = get_option( 'pk_sak' );
			$options = is_array($options) ? $options : array();
			if(!array_key_exists('pk_sak_tools', $options)){ return false; }

			$tools = __PKSAK_USR_DIR__.'/tools/';
			$tools_arr = scandir($tools);
			if(!$tools_arr || !array($tools_arr)){ return; }
			$dir = array_diff($tools_arr, array('..', '.'));

			$valid = array();
			foreach($dir as $temp){
				if(!is_dir($tools.$temp)){ continue; } //Not a directory, get out
				if(!file_exists($tools.$temp.'/pk-tool-'.$temp.'.php')){ continue; } //relevant pk file doesn't exist, get out
				$enable = file_exists($tools.$temp.'/pk_enabled.txt') || file_exists($tools.$temp.'/.enabled') ? true : false; //Should it always be enabled?
				$name = false;
				$name = $name ? $name : $tools.$temp.'/pk-tool-'.$temp;
				$valid[$temp] = array(
					'file'=>'pk-tool-'.$temp,
					'always_enabled'=>$enable
				);
			}

			foreach($valid as $dir => $info){
				$id = $info['file'];
				if ( isset( $options['pk_sak_tools'][ $id ] ) || $info['always_enabled'] ) {
					//Tool is enabled, include it
					include_once($tools.$dir.'/'.$id.'.php');
				}
			}

		}

	}

}

/**
 * Register plugin activation.
 */
register_activation_hook( __FILE__, array( 'Powderkeg_Swiss_Army_Knife', 'activate' ) );

/**
 * Initialize the plugin.
 */
add_action( 'plugins_loaded', array( 'Powderkeg_Swiss_Army_Knife', 'get_instance' ), 0 );
