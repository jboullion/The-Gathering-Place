<?php
	
/*
 * Lity Lightbox Tool
 * Base functionality created by Jan Sorgalla 
 * View the standalone project here: http://sorgalla.com/lity/
 */
if(ENVIRONMENT != 'dev') { return; }
if ( defined( 'ABSPATH' ) && ! class_exists( 'PK_Tool_FOOTER_DEBUG' ) ) {
	//if SAVEQUERIES is not set to true the wordpress queries will not be recorded for debugging
	define('SAVEQUERIES', true);

	class PK_Tool_FOOTER_DEBUG {		

		public function __construct() {
			add_action('wp_footer', array($this,'pk_show_debug_info'), 1001); //1001 so that it also comes after our caching module at priority 1000
		}

		public function pk_show_debug_info() {
			global $wpdb;

			echo '<div id="debug-container">';
			?>
			<style>
				#debug-container { background-color: #FFF; width: 98%; margin: 0px auto; }

				#debug-container table { width: 100%; }
				#debug-container table td,
				#debug-container table th { padding: 5px; }

				.hide-array > div.array-display { display: none; }
				.hide-array > div.opener { cursor: pointer; }
				.pk-debug { margin-bottom: 50px; }

				.pk-debug.gets table th { background-color: #2ECC40; color: #FFF; } 
				.pk-debug.gets table td,
				.pk-debug.gets table th { border: 1px solid #2ECC40; } 

				.pk-debug.posts table th { background-color: #FF4136; color: #FFF; } 
				.pk-debug.posts table td,
				.pk-debug.posts table th { border: 1px solid #FF4136; } 

				.pk-debug.acf table th { background-color: #001f3f; color: #FFF; } 
				.pk-debug.acf table td,
				.pk-debug.acf table th { border: 1px solid #001f3f; } 

				.pk-debug.cookie table th { background-color: #01FF70; color: #FFF; } 
				.pk-debug.cookie table td,
				.pk-debug.cookie table th { border: 1px solid #01FF70; } 

				.pk-debug.session table th { background-color: #FF851B; color: #FFF; } 
				.pk-debug.session table td,
				.pk-debug.session table th { border: 1px solid #FF851B; } 

				.pk-debug.server table th { background-color: #FF3300; color: #FFF; } 
				.pk-debug.server table td,
				.pk-debug.server table th { border: 1px solid #FF3300; } 

				.pk-debug.globals table th { background-color: #85144b; color: #FFF; } 
				.pk-debug.globals table td,
				.pk-debug.globals table th { border: 1px solid #85144b; } 

				.pk-debug.queries table th { background-color: #0074D9; color: #FFF; } 
				.pk-debug.queries table td,
				.pk-debug.queries table th { border: 1px solid #0074D9; } 
			</style>
			<script>
				jQuery(document).ready(function($){
					$('.hide-array .opener').click(function(e){
						$(this).next().toggle();
						if($(this).hasClass('closed')){
							$(this).removeClass('closed').addClass('open');
							$(this).html('<i class="fa fa-folder-open-o"></i> Close');
						}else{
							$(this).removeClass('open').addClass('closed');
							$(this).html('<i class="fa fa-folder-o"></i> Open');
						}
					});
				});
			</script>
			<?php

			$this->pk_display_debug_info($_GET, '$_GET', 'gets');

			$this->pk_display_debug_info($_POST, '$_POST', 'posts');

			if(function_exists('get_fields')){
				$pk_acf_fields = get_fields();
				$this->pk_display_debug_info($pk_acf_fields, 'ACF', 'acf');
			}

			$this->pk_display_debug_info($_COOKIE, '$_COOKIE', 'cookie');

			$this->pk_display_debug_info($_SESSION, '$_SESSION', 'session');

			$this->pk_display_debug_info($_GLOBALS, '$_GLOBALS', 'globals');

			$this->pk_display_debug_info($_SERVER, '$_SERVER', 'server');

			//pk_display_debug_info($wpdb->queries, 'WordPress Queries', 'queries', array('#','Query','Response Time'));

			if(! empty($wpdb->queries)){
				$total_time = 0;
				echo '<div class="pk-debug queries">
					<h2>WordPress Queries</h2>
					<table>
						<tr>
							<th>#</th>
							<th>Query</th>
							<th>Response Time</th>
						</tr>';

				foreach($wpdb->queries as $key => $query){
					echo '<tr>
							<td valign="top">'.$key.'</td>
							<td style="word-break: break-all;">'.$query[0].'</td>
							<td valign="top">'.$query[1].'</td>
						</tr>';
					$total_time += $query[1];
				}

				echo '<td colspan="2" align="right">Total Time:</td>
					<td>'.$total_time.'</td>';
				echo '</table></div>';
			}

			echo '</div>';
		}

		//Display an array of variables 
		private function pk_display_debug_info($data, $title, $class, $headers = array('#','Key','Value')){
			if(! empty($data)){
				echo '<div class="pk-debug '.$class.'">
					<h2>'.$title.'</h2>
					<table>
						<tr>
							<th>'.$headers[0].'</th>
							<th>'.$headers[1].'</th>
							<th>'.$headers[2].'</th>
						</tr>';

				$index = 1;	
				foreach($data as $key => $pk_data){
					if(empty($pk_data)) continue;
					if(is_array($pk_data)){
						foreach($pk_data as $pkey => $pk_sub_data){
							if(empty($pk_sub_data)) continue;
							echo '<tr>
								<td>'.$index++.'</td>
								<td>'.$key.'['.$pkey.']</td>';

								if(is_array($pk_sub_data) || is_object($pk_sub_data)){
									echo '<td><div class="hide-array">
										<div class="opener closed"><i class="fa fa-folder-o"></i> Open</div><div class="array-display">';
									pk_print($pk_sub_data);
									echo '</div></div></td>';
								}else{
									echo '<td>'.$pk_sub_data.'</td>';
								}
								
							echo '</tr>';
						}
					}else{
						echo '<tr>
							<td>'.$index++.'</td>
							<td>'.$key.'</td>';

							if(is_array($pk_data) || is_object($pk_data)){
								echo '<td><div class="hide-array">
										<div class="opener closed"><i class="fa fa-folder-o"></i> Open</div><div class="array-display">';
								pk_print($pk_sub_data);
								echo '</div></div></td>';
								
							}else{
								echo '<td>'.$pk_data.'</td>';
							}
							
						echo '</tr>';
					}
					
				}

				echo '</table></div>';
			}
		}

		/**
		* Used to debug a query. Will display any errors generated by the query.
		* 
		* $results = $wpdb->getresults('some query');
		* debug_query($result);
		*/
		public function debug_query( $result )
		{
		    global $wpdb, $blog_id;

		    $wpdb->show_errors     = true;
		    $wpdb->suppress_errors = false;

		    $output = '<pre style="white-space:pre-line;">';
		        $output .= 'Last Error: ';
		        $output .= var_export( $wpdb->last_error, true );

		        $output .= "\n\nLast Query: ";
		        $output .= var_export( $wpdb->last_query, true );

		        if ( false === $result )
		        {
		            $result = new WP_Error( 'query_failed', 'No update.', 'test 1' );
		        }
		        elseif ( 0 === $result )
		        {
		            $result = new WP_Error( 'update_failed', 'Updated zero rows.', 'test 2' );
		        }
		        elseif ( 0 < $result )
		        {
		            $result = 'Success';
		        }
		    $output .= '</pre>';

		    // Only abort, if we got an error
		    is_wp_error( $result ) 
		        AND exit( $output.$result->get_error_message() );
		    
		}

		public function pk_recursive_value_display($item, $key){
			 return "$key holds $item\n";
		}

	}

	new PK_Tool_FOOTER_DEBUG;

}