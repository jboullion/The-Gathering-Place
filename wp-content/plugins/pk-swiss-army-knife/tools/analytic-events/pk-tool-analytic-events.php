<?php
	if ( defined( 'ABSPATH' ) && !class_exists( 'PK_Tool_Analytics_Events' ) && class_exists('GFForms') ) {

		class PK_Tool_Analytics_Events {	

			public function __construct() {
				add_filter( 'gform_confirmation' , array( $this, 'pk_send_ga_event' ), 10, 4 );
			}

			/**
			 * Whenever a Gravity Form is submitted, we will attempt to save an event to Google Analytics.
			 */
			public function pk_send_ga_event( $confirmation, $form, $entry, $ajax ) {
				if(is_array($confirmation)){ return $confirmation; }
				$markup = $confirmation;
				$markup .= "<script>
								if (typeof __gaTracker != 'undefined') {
									__gaTracker('send', {
										hitType: 'event',
										eventCategory: 'Form Submission',
										eventAction: 'submission',
										eventLabel: ".json_encode($form['title']).",
										eventValue: '".$entry['id']."'
									});

								}
							</script>";

				return $markup;
			}
			
		}

		new PK_Tool_Analytics_Events;
	}