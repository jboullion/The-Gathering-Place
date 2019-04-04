<?php

/*
 * PDF JS Viewer Tool
 * Base functionality created by Mozilla
 * View the standalone project here: https://github.com/mozilla/pdf.js/
 */

if ( defined( 'ABSPATH' ) && ! class_exists( 'PK_PDF_VIEWER' ) ) {

	class PK_PDF_VIEWER {		

		public function __construct() {
			$this->pdf_path = plugins_url('lib/pdf/web/viewer.html?file=', __FILE__);
			add_action('wp_footer', array($this,'pk_wp_footer_pdf'));
		}

		public function pk_wp_footer_pdf(){
			$ie = pk_detect_ie();
			if(!$ie || $ie > 8):
				$path = '';
		?>
			<script type="text/javascript">
				var links = document.getElementsByTagName('a');
				var host = location.hostname;
				for (var i = 0; i < links.length; i++) {
					if (links[i].href.substr(links[i].href.length - 4) == '.pdf' && links[i].href.indexOf(host) !== -1) {
						links[i].addEventListener("click", function(e){ 
							var pdf_path = '<?php echo $this->pdf_path; ?>';
							var pdf_url = this.href;
							window.open(pdf_path+pdf_url);
							e.preventDefault();
						});
					}
				}
			</script>
		<?php	endif;
		}

		// detect/return Internet Explorer version
		private function pk_detect_ie() {

			// user agent string
			$agent = $_SERVER['HTTP_USER_AGENT'];

			// detect version
			$version = null;
			if(preg_match('/MSIE (.*?);/', $agent, $match) || preg_match('/Trident\/[^\;]+;\s+rv\:([^\)]+)\)/', $agent, $match)) {
				$version = floor(trim($match[1]));
			}
		
			// return ie version
			return $version;
		} 

	}

	new PK_PDF_VIEWER;
}
