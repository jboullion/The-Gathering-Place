<?php

	// if the events calendar class doesn't exist, get out
	if(!class_exists('TribeEvents')) { return; }

	// prevents wordpress throwing a 404 code when there are no events to show
	add_filter('status_header', 'pk_tool_avoid_tribe_month_view_404s', 1);
	if(!function_exists('pk_tool_avoid_tribe_month_view_404s')){
		function pk_tool_avoid_tribe_month_view_404s($status) {
			global $wp_query;

			if(!isset($wp_query->query_vars['eventDisplay']) || false === strpos($status, '404 Not Found')) {
				return $status;
			}

			$wp_query->is_404 = false;
			return str_replace('404 Not Found', '200 OK', $status);
		}
	}