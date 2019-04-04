<?php

if(ENVIRONMENT != 'dev') { return; }
if ( defined( 'ABSPATH' ) && ! class_exists( 'PK_Tool_SCRIPT_TIMER' ) ) {

	class PK_Tool_SCRIPT_TIMER {
		public $script_start = 0;
		public $script_finish = 0;

		public function __construct() {
			add_action('wp_head', array($this,'start_time'), 1);
			add_action('wp_footer', array($this,'stop_time'), 1000);
		}

		/**
		 * Begin recording script time
		 */
		public function start_time(){
			$this->script_start = microtime(true);
		}

		/**
		 * Stop recording script time and display results
		 */
		public function stop_time(){
			$this->script_finish = microtime(true);
			$time = number_format(($this->script_finish - $this->script_start), 4);
			?>

			<style>
				#pk-script-timer { background-color: #FFF; color: #dd2345; padding: 30px; text-align: center;  }
			</style>

			<?php 
			echo '<div id="pk-script-timer">
					<h3>SCRIPT TIME: '.$time. ' seconds</h3>
				</div>';
		}

	}

	new PK_Tool_SCRIPT_TIMER;

}