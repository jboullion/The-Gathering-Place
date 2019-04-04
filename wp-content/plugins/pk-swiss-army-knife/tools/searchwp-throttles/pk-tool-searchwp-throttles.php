<?php 
/*
Plugin Name: Search-WP Throttling
Description: Throttle the Search-WP indexing so it doesn't kill the site if it is very large.
Version: 1.0.0
Author: Nick Kalscheur
Author URI: https://kalscheur.info/
*/

if ( defined( 'ABSPATH' ) && !class_exists( 'PK_Tool_SearchWP_Throttling' ) && class_exists( 'SearchWP' ) ) {

	class PK_Tool_SearchWP_Throttling {		

		public function __construct() {
			//checking if we are using WP Engine and if the WPE_GOVERNMOR has been set
			if(! defined('WPE_GOVERNOR') && class_exists('WpeCommon') ){
				//disable the WP ENGINE query governor. 
				//https://searchwp.com/docs/kb/attention-wp-engine-customers-no-results-long-query-killed-query-notices/
			   define( 'WPE_GOVERNOR', false );
			}

			/* searchwp throttles */
			// pause the indexer for 1s in between passes
			add_filter('searchwp_indexer_throttle', array( $this, 'pk_searchwp_indexer_throttle'));
			// only process 3 posts per indexer pass (instead of the default of 10)
			add_filter('searchwp_index_chunk_size', array( $this, 'pk_searchwp_index_chunk_size'));
			// only process 200 terms at a time
			add_filter('searchwp_process_term_limit', array( $this, 'pk_searchwp_process_term_limit'));
		}

		public function pk_searchwp_indexer_throttle() { return 1; }
		public function pk_searchwp_index_chunk_size() { return 3; }
		public function pk_searchwp_process_term_limit() { return 200; }
		   
	}

	new PK_Tool_SearchWP_Throttling;
}
