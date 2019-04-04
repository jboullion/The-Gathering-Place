<?php 
/*
Plugin Name: Event Calendar Options
Description: A couple of common changes to The Events Calendar.
Version: 1.0.0
*/

if ( class_exists( 'Tribe__Events__Main' ) && ! class_exists( 'PK_Tool_EVENT_CALENDAR_OPTIONS' ) ) {


	class PK_Tool_EVENT_CALENDAR_OPTIONS{		

		public function __construct() {
			add_action('init', array($this, 'init'), 1000);
		}

		function init(){
			remove_action('tribe_events_single_event_after_the_content', array('Tribe__Events__iCal', 'single_event_links'));
			add_action('tribe_events_single_event_after_the_content',  array($this,'pk_customized_tribe_single_event_links'));

			//add_filter( 'tribe_ical_properties', array($this,'modify_ical_output') );

			add_filter( 'tribe_events_the_next_month_link', array($this,'edit_next_link'), 10, 1);

			add_action( 'register_post_type_args',  array($this,'remove_venue_search'), 1, 2);

			add_action('wp_print_styles', array($this,'remove_tribe_styles'),1);
			add_action('wp_print_scripts', array($this,'remove_tribe_scripts'),1);

			//do not use tha CALNAME in the ics file
			add_filter( 'tribe_ical_feed_calname', '__return_false' );
		}

		// Changes the text labels for Google Calendar and iCal buttons on a single event page
		function pk_customized_tribe_single_event_links()    {
		    if (is_single() && post_password_required()) {
		        return;
		    }
		 
		    echo '<div class="tribe-events-cal-links">';
		    echo '<a class="tribe-events-gcal tribe-events-button" href="' . tribe_get_gcal_link() . '" title="' . __( 'Add to Google Calendar', 'tribe-events-calendar-pro' ) . '">+ Add to Google Calendar </a>';
		    echo '<a class="tribe-events-ical tribe-events-button" href="' . tribe_get_single_ical_link() . '">+ Add to Outlook </a>';
		    echo '</div><!-- .tribe-events-cal-links -->';

		    remove_action('tribe_events_single_event_after_the_content', array('Tribe__Events__iCal', 'single_event_links'));
		}

		/*
		//A fix for the Outlook import of an event. Removing the calendar name value.
		function modify_ical_output( $ics ) {
		    //$find_pattern = "X-WR-CALDESC: Events for .* \r\n";

		    //for some users, the calender name can cause issues with importing into outlook.
		    $find_pattern = "/X-WR-CALNAME:.*\r\n/";
		    $replace_with = ''; // Remove completely
		    return preg_replace( $find_pattern, $replace_with, $ics );
		}
		*/

		//Always show the month navigation
		function edit_next_link($html){
			$date = TribeEvents::instance()->nextMonth( tribe_get_month_view_date() );
			
			$tribe_url = get_option('tribe_events_calendar_options');

			$url = '/'.$tribe_url['eventsSlug'].'/'.$date;
			
			$html = '<a data-month="' . $date . '" href="' . $url . '" rel="next">' . date('F', strtotime($date)) . ' <span>&raquo; </span></a>';
			
			return $html;
		}

		//Remove the venues from the search results type from searches
		function remove_venue_search( $args, $post_type ) {
			if ( 'tribe_venue' == $post_type ){
				$args['exclude_from_search'] = true ;
			}

			return $args;
		}

		function is_tribe(){
			if (tribe_is_event() || tribe_is_event_category() || tribe_is_in_main_loop() || tribe_is_view() || 'tribe_events' == get_post_type() || is_singular( 'tribe_events' )) {
				return true;
			}else{
				return false;
			}
		}
		
		function remove_tribe_styles() {
			if ( ! $this->is_tribe()) {
				//wp_dequeue_style( 'tribe_events-admin-ui' );
				wp_dequeue_style( 'tribe-events-mini-calendar' );
				wp_dequeue_style( 'tribe-events-calendar-style' );
				wp_dequeue_style( 'widget-this-week-pro-style' );
				wp_dequeue_style( 'tribe_events-widget-this-week-pro-style' );
			}
		}
		
		
		function remove_tribe_scripts() {
			if ( ! $this->is_tribe()) {
				//wp_dequeue_style( 'tribe_events-admin-ui' );
				wp_dequeue_script( 'tribe-events-calendar-script' );
				wp_dequeue_script( 'tribe-events-pjax' );
				wp_dequeue_script( 'tribe-events-mini-calendar' );
				wp_dequeue_script( 'jquery-ecp-plugins' );
				wp_dequeue_script( 'chosen-jquery' );
				wp_dequeue_script( 'tribe_events-admin' );
				wp_dequeue_script( 'tribe-this-week' );
			}

		}
	}

	new PK_Tool_EVENT_CALENDAR_OPTIONS;
}