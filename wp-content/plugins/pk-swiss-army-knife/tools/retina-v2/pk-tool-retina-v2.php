<?php
/*
Plugin Name: Retina JS - V2
Plugin URI: https://github.com/xorcery/retinajs
Description: Enqueue the retina.js script for @2x image checking / inclusion <strong>This second iteration requires you to add 'data-rjs="2"' to the image elements you want to be retina</strong>.
Version: 2.0.0
Author: Nick Kalscheur
Author URI: https://kalscheur.info/
*/
/*
 * Retina JS
 * Base functionality created by Axial
 * View the standalone project here: http://axial.agency/
 */

if ( defined( 'ABSPATH' ) && ! class_exists( 'PK_Tool_Retina' ) ) {

	class PK_Tool_Retina_V2 {		

		private function retina_folder(){
			return __PKSAK_TOOLS__.'/'.$this->PARENT_FOLDER;
		}

		public function retina_enqueue_scripts(){
			wp_enqueue_script( 'pk-retina-v2-js', plugins_url($this->retina_folder().'/js/retina.min.js'), 'jQuery','2',true );		
		}


		public function __construct() {
			$this->PARENT_FOLDER = basename(dirname(__FILE__));
			add_action( 'wp_enqueue_scripts', array( $this, 'retina_enqueue_scripts' ) );
		}

	}

	new PK_Tool_Retina_V2;
}

