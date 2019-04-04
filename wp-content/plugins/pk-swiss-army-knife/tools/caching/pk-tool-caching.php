<?php 
/*
Plugin Name: Page Caching
Description: Do some basic caching to try to load html files instead of reloading from server each time. Reforms the cache for each page/post on each save.
Version: 1.0.0
Author: James Boullion
*/

if ( defined( 'ABSPATH' ) && !class_exists( 'PK_Tool_Caching' ) ) {

	class PK_Tool_Caching {		

		public function __construct() {
			add_action('init', array($this,'pk_begin_cache'), 500);
			add_action('wp_footer', array($this,'pk_end_cache'), 1000); 
			add_action('save_post', array($this,'pk_clear_cache'));
		}

		//must be run after init so that custom post types have been registered
		public function pk_begin_cache() {
			//Check is this page is an exception to the cache rule
			if(!apply_filters('pk_tool_do_cache',true)){ return; }

			if(!is_admin() && !$this->pk_is_login_page()) {
				global $cachefile, $pk_buffering;
				
				// special case for the homepage, otherwise, attempt to grab the actual post id
				$post_id = preg_match('/^\/(\?|$)/', $_SERVER['REQUEST_URI']) ? $this->pk_get_homepage_filename() : url_to_postid('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);



				// if we found a post id and can get the cache file name
				if($post_id && ($cachefile = $this->pk_get_cachefile($post_id))) {
					
					// if the file already exists, display the content and gtfo
					if(file_exists($cachefile)) {
						readfile($cachefile); exit();
					}
					
					// could try ob_get_level() but that can be incorrect so just set boolean
					$pk_buffering = true;
					
					// if we're still here, we need to generate a cache file
					ob_start();
				}
			}
		}

		private function pk_is_login_page() { return in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php')); }

		//execute after the footer. with priority 1000 should execute after all other actions have been run
		public function pk_end_cache() {
			global $pk_buffering, $cachefile;
			
			if($pk_buffering && !is_admin()) {
				
				// now the script has run, generate a new cache file
				$fp = fopen($cachefile, 'w'); 

				// save the contents of output buffer to the file
				fwrite($fp, ob_get_contents()."<!-- Cached on ".date('F j, Y \a\t h:i:s a', pk_time())." -->\n</body></html>");
				fclose($fp); 

				ob_end_flush();
			}
		}
		
		// delete the cached file anytime the post is saved
		public function pk_clear_cache($post_id) {

			// special case for the homepage
			if($post_id == get_option('page_on_front')) {
				$post_id = $this->pk_get_homepage_filename();
			}

			if(file_exists($cachefile = $this->pk_get_cachefile($post_id))) {
				unlink($cachefile);

				// TODO: recache the file via wget/curl or something
			}
		}

		public function pk_get_cachefile($post_id = null, $ext = 'html'){
			$this->pk_set_cachefile_dir();

			// if no post_id is passed or zero is passed, don't save it
			if(!$post_id) { return false; }

			$dir = wp_upload_dir();

			// cache file to either load or create
			return $dir['basedir'].'/pk_cache/'.$post_id.'.'.$ext;
		}

		//Create the directory if it doesn't exist
		private function pk_set_cachefile_dir(){
			$dir = wp_upload_dir();
			$cachefile_dir = $dir['basedir'].'/pk_cache/';
			if (!is_dir($cachefile_dir)){
			    mkdir($cachefile_dir, 0755, true);
			}
		}

		private function pk_get_homepage_filename() { return apply_filters('pk_tool_caching_homepage_filename','home'); }
		   
	}

	new PK_Tool_Caching;
}
