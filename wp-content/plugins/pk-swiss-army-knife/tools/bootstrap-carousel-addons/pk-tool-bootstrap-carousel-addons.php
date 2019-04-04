<?php
if ( defined( 'ABSPATH' ) && ! class_exists( 'PK_Tool_Retina' ) ) {

	class PK_Tool_Bootstrap_Carousel_Addons {		

		private function retina_folder(){
			return __PKSAK_TOOLS__.'/'.$this->PARENT_FOLDER;
		}

		public function bootstrap_carousel_addon_enqueue_scripts(){
			wp_enqueue_script( 'pk-bootstrap-carousel-addons-js', plugins_url($this->retina_folder().'/js/bootstrap_carousel_addons.js'), 'jQuery','2',true );		
		}


		public function __construct() {
			$this->PARENT_FOLDER = basename(dirname(__FILE__));
			add_action( 'wp_enqueue_scripts', array( $this, 'bootstrap_carousel_addon_enqueue_scripts' ) );
		}

	}

	new PK_Tool_Bootstrap_Carousel_Addons;
}

