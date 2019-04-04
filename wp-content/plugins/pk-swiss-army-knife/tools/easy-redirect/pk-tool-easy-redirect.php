<?php
/*
	Plugin Name: Easy Redirection
	Description: Plugin used by Powderkeg Web Design to create URL redirections inside the admin
	Plugin URI:  http://www.powderkegwebdesign.com/
	Version:     1.0.1
	Author:      James Boullion
	Author URI:  http://www.powderkegwebdesign.com/
*/
	

global $wpdb;
if ( ! defined( 'PK_REDIRECT_TABLE' ) )
	define( 'PK_REDIRECT_TABLE', $wpdb->prefix.'pk_redirection' );

if ( ! defined( 'PK_REDIRECT_URL' ) )
	define( 'PK_REDIRECT_URL', plugin_dir_url( __FILE__ ) );

if ( ! defined( 'PK_REDIRECT_PATH' ) )
	define( 'PK_REDIRECT_PATH', plugin_dir_path( __FILE__ ) );


//This is the actually redirection functionality
if(! is_admin() && $_SERVER['REQUEST_URI'] !== '/'){
	$uri = strtolower($_SERVER['REQUEST_URI']);
	$uri = pk_add_slash($uri);
	$uri = pk_strip_siteurl($uri,$_SERVER);
	$uri = pk_add_slash($uri); // doing it again because I can... and it may have been removed by pk_strip_siteurl()

	//$uri = rtrim($uri,'/'); //remove the last slash if it exists
	
	$query = 'SELECT * FROM '.PK_REDIRECT_TABLE.' WHERE source LIKE "'.esc_sql($uri).'" AND enabled = 1 LIMIT 1';
	$result = $wpdb->get_results($query, ARRAY_A);

	if(! empty($result)){
		$wpdb->update( 
			PK_REDIRECT_TABLE, 
			array( 
				'hits' => (int)$result[0]['hits'] + 1,	
				'last_hit' => date('Y-m-d H:i:s')	
			), 
			array( 'redirect_id' => $result[0]['redirect_id'] ), 
			array( 
				'%d',	
				'%s'	
			), 
			array( '%d' ) 
		);
		$target = pk_maybe_add_subdir($result[0]['target']);
		pk_redirect( $target, $result[0]['redirect_type'] );
		exit;
	}
}

//Add the redirect page to the menu
add_action('admin_menu','_pk_add_easy_redirect',20); //put this near the bottom of the tools
function _pk_add_easy_redirect(){
	add_submenu_page('tools.php', 'Easy Redirect', 'Easy Redirect', 'edit_users', 'pk-redirection', 'pk_redirection_page');
}

//This is the redirection page
function pk_redirection_page() {
	global $wpdb;
	
	//enque thinkbox so we can use admin modals for updates
	add_thickbox();
	
	$redirect_table = $wpdb->prefix.'pk_redirection';

	//make sure the table exists
	$wpdb->query("CREATE TABLE IF NOT EXISTS $redirect_table (
			  `redirect_id` int(11) NOT NULL AUTO_INCREMENT,
			  `source` varchar(255) NOT NULL,
			  `target` varchar(255) NOT NULL,
			  `redirect_type` varchar(3) NOT NULL DEFAULT '301',
			  `hits` int(11) NOT NULL DEFAULT '0',
			  `last_hit` datetime DEFAULT NULL,
			  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			  `enabled` tinyint(2) NOT NULL DEFAULT '1',
			  PRIMARY KEY (`redirect_id`),
			  UNIQUE KEY `source` (`source`)
			) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1");

	//handle CSV file redirection upload
	if(! empty($_FILES['csv'])){
		pk_upload_redirect_csv($_FILES['csv']);
	}

	
	if(! empty($_POST['submit']) && $_POST['submit'] == "Add Redirect" && ! empty($_POST['source']) && ! empty($_POST['target'])){
		
		$cleaned_source = pk_redirect_clean($_POST['source']);
		$cleaned_target = pk_redirect_clean($_POST['target']);
		
		$insert_result = $wpdb->insert( $redirect_table, 
									array( 
										'source' => $cleaned_source, 
										'target' => $cleaned_target,
										'redirect_type' => $_POST['redirect_type'] 
									), 
									array( 
										'%s', 
										'%s',
										'%s' 
									) 
								);
								
	}else if(! empty($_POST['submit']) && $_POST['submit'] == "Update Redirect" && ! empty($_POST['edit']['source']) && ! empty($_POST['edit']['target'])){
		
		$cleaned_source = pk_redirect_clean($_POST['edit']['source']);
		$cleaned_target = pk_redirect_clean($_POST['edit']['target']);
	
		$update_result = $wpdb->update( $redirect_table, 
									array( 
										'source' => $cleaned_source, 
										'target' => $cleaned_target,
										'redirect_type' => $_POST['edit']['redirect_type'] 
									), 
									array( 'redirect_id' => $_POST['edit']['redirect_id'] ), 
									array( 
										'%s', 
										'%s',
										'%s' 
									),
									array( '%d' )  
								);
	}

	//Inform the user about actions they just took
	if($_GET['delete_id']) {
		$remove_redirect = $wpdb->get_row('SELECT * FROM '.$redirect_table.' WHERE redirect_id = '.(int)$_GET['delete_id']);
	}

	if($_GET['delete_confirm']) {
		$removed_redirect = $wpdb->get_row('SELECT * FROM '.$redirect_table.' WHERE redirect_id  = '.(int)$_GET['delete_confirm']);
		$wpdb->query('DELETE FROM '.$redirect_table.' WHERE redirect_id  = '.(int)$_GET['delete_confirm']);
	}

	if($_GET['disable_id']) {
		$disabled_redirect = $wpdb->get_row('SELECT * FROM '.$redirect_table.' WHERE redirect_id = '.(int)$_GET['disable_id']);
		$wpdb->query('UPDATE '.$redirect_table.' SET enabled = 0 WHERE redirect_id = '.(int)$_GET['disable_id']);
	}
	
	if($_GET['enable_id']) {
		$enabled_redirect = $wpdb->get_row('SELECT * FROM '.$redirect_table.' WHERE redirect_id = '.(int)$_GET['enable_id']);
		$wpdb->query('UPDATE '.$redirect_table.' SET enabled = 1 WHERE redirect_id = '.(int)$_GET['enable_id']);
	}
	
	//include the HTML that creates our form
	include 'includes/redirect-form.php';

}

/**
 * Insert all of the redirects from a CSV
 * 
 * @param  string $csv  the CSV information from the "$_FILES" global
 */
function pk_upload_redirect_csv($csv){
	if(empty($csv) ) return;

	global $wpdb;

	$tmpName = $csv['tmp_name'];

	//Allows Excel exported CSVs to be read normally
	ini_set('auto_detect_line_endings', TRUE);

	//NOTE: Sometimes csv files saved with Excel will not work correctly
	if (function_exists('str_getcsv')) {
		
		//PHP >= 5.3 
		$csvFile = file($tmpName);
		$csvAsArray = array_map('str_getcsv', $csvFile);
	}else{
		//PHP < 5.3 
		$csvAsArray = array();
		$file = fopen($tmpName, 'r');

		while (($result = fgetcsv($file)) !== false)
		{
		    $csvAsArray[] = $result;
		}

		fclose($file);
	}
	
	foreach($csvAsArray as $key => $redirect){
		//pk_print($redirect);
		
		if($key == 0 && $_POST['use_first'] != 1) continue;

		$cleaned_source = pk_redirect_clean($redirect[0]);
		$cleaned_target = pk_redirect_clean($redirect[1]);
		
		$type = (empty($redirect[2])? 301:$redirect[2]);

		$wpdb->insert( $redirect_table, 
						array( 
							'source' => $cleaned_source, 
							'target' => $cleaned_target,
							'redirect_type' => $type 
						), 
						array( 
							'%s', 
							'%s',
							'%s' 
						) 
					);
	}
	
}

/**
 * Clean out the URI so that it is easier to work with. Returns external URIs without cleaning and returns internal URIs without the domain.
 *
 * Not super useful atm but makes the URLs and data easier to read in the DB
 * 
 * TODO pk: CLEAN CLEAN CLEAN. Keep looking for better ways to clean source and target urls
 * 
 * @param  string $uri  the url we need to clean.
 * @return string      	cleaned URI 
 */
function pk_redirect_clean($uri){
	$site_url = get_site_url();
	
	//separate out the protocol so we can check for that independently
	$site_url = preg_replace('/https?:\/\//', '', $site_url);
	
	//is this a site outside of our domain? if so do not touch the uri
	if (strpos($uri,'http') !== false) {
		//https vs http can cause issues so lets remove to just test the actual domain name
		$test_uri = preg_replace('/https?:\/\//', '', $uri);
		if(substr($test_uri,0,strlen($site_url)) != $site_url){
			//if this is an external url return the same $uri we were sent. We are not cleaning those
			return $uri;
		}
	}
	
	//remove the sites domain. probably not needed but cleaner in the DB and admin.
	$cleaned_uri = preg_replace('/https?:\/\/'.preg_quote($site_url,'/').'/', '',$uri);
	
	if(substr($cleaned_uri, 0, 1) != '/'){
		$cleaned_uri = '/'.$cleaned_uri;
	}
	

	$cleaned_uri = strtolower(pk_add_slash($cleaned_uri));
	
	
	return $cleaned_uri;
}

//we are checking some different options in our URI / URL to know if we need to add a slash to the uri
function pk_add_slash($uri){
	if(substr($uri, -1) != '/' && substr($uri, -4) != '.php' && substr($uri, -4) != '.pdf' && substr($uri, -5) != '.html' && strpos($uri,'/?') === false && strpos($uri, '/#') === false){
		return  $uri.'/';;
	}

	return  $uri;
}

//For sites installed in a sub directory, we need to remove the leading subdirectory from the server URI since they aren't saved in the DB that way.
function pk_strip_siteurl($uri,$s){
	
	$replace = '';

	if(! empty($s['REDIRECT_SCRIPT_URI'])){
		$site_url = get_site_url();
		$host = $s['REDIRECT_SCRIPT_URI'];
		$replace = str_replace($site_url,'',$host);
	}

	return ! empty($replace) ? $replace : $uri;
	
}

//If we're in a site instsalled in a sub directory, make sure to append the sub directory to the relative targets
function pk_maybe_add_subdir($target){
	$site_url = get_site_url();
	$target = pk_redirect_clean($target);
	preg_match("/\..*\/([^\/]+)/", $site_url, $addition);
	$addition = isset($addition[1]) ? '/'.$addition[1] : '';

	if (strpos($target, 'http') === false) {
		$target = $addition.$target;
	}

	return $target;
}

function pk_redirect( $url, $status ) {
	global $is_IIS;

	//if this is on a Windows IIS server we need to use a different header value
	if ( $is_IIS ) {
		header( "Refresh: 0;url=$url" );
		exit();
	}else{
		header( "Location: $url" );
		exit();
	}
	/*
	elseif ( $status === 301 && php_sapi_name() === 'cgi-fcgi' ) {
		$servers_to_check = array( 'lighttpd', 'nginx' );

		foreach ( $servers_to_check as $name ) {
			if ( stripos( $_SERVER['SERVER_SOFTWARE'], $name ) !== false ) {
				pk_status_header( $status );
				header( "Location: $url" );
				exit( 0 );
			}
		}
	}

	pk_status_header( $status );
	return $url;
	*/
}

/*
function pk_status_header( $status ) {
	// Fix for incorrect headers sent when using FastCGI/IIS
	if ( substr( php_sapi_name(), 0, 3 ) === 'cgi' )
		return str_replace( 'HTTP/1.1', 'Status:', $status );
	return $status;
}
*/
