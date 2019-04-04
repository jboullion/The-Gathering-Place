<?php
/*
Plugin Name: Retina JS
Plugin URI: http://axial.agency
Description: Enqueue the retina.js script for @2x image checking / inclusion
Version: 1.0.0
Author: Nick Kalscheur
Author URI: https://kalscheur.info/
*/
/*
 * Retina JS
 * Base functionality created by Axial
 * View the standalone project here: http://axial.agency/
 */

if ( defined( 'ABSPATH' ) && ! class_exists( 'PK_Tool_Retina' ) ) {

	class PK_Tool_Retina {		

		private function retina_folder(){
			return __PKSAK_TOOLS__.'/'.$this->PARENT_FOLDER;
		}

		public function retina_enqueue_scripts(){
			wp_enqueue_script( 'pk-retina-js', plugins_url($this->retina_folder().'/js/retina.min.js'), 'jQuery','1',true );		
		}


		public function __construct() {
			$this->PARENT_FOLDER = basename(dirname(__FILE__));
			add_action( 'wp_enqueue_scripts', array( $this, 'retina_enqueue_scripts' ) );
		}

	}

	new PK_Tool_Retina;
}

