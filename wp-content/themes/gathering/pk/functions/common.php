<?php
	
	// prints out an array with <pre> tags
	function pk_print($input, $force = false) { 
		if(ENVIRONMENT != 'live' || $force === true){
			echo '<pre class="pk-print">'.print_r($input, true).'</pre>'; 
		}
	}

	// Defer jQuery Parsing using the HTML5 defer property
	//http://www.laplacef.com/2014/05/24/how-to-defer-parsing-javascript-in-wordpress/
	if (!(is_admin() )) {
		function defer_parsing_of_js ( $url ) {
			if ( FALSE === strpos( $url, '.js' ) ) return $url;
			if ( strpos( $url, 'jquery.js' ) ) return $url;
			// return "$url' defer ";
			return "$url' defer onload='";
		}
		add_filter( 'clean_url', 'defer_parsing_of_js', 11, 1 );
	}


	//remove jquery migrate
	add_filter( 'wp_default_scripts', 'dequeue_jquery_migrate' );
	function dequeue_jquery_migrate( &$scripts){
		if(!is_admin()){
			$scripts->remove( 'jquery');
			$scripts->add( 'jquery', false, array( 'jquery-core' ), '1.10.2' );
		}
	}

	// checks to see if the current user is super admin
	function pk_is_admin() {
		global $current_user;
		return in_array('administrator', $current_user->roles);
	}

	//checks if the user is superadmin OR webadmin
	function pk_is_site_admin($user = null){
		global $current_user;

		if( ! empty($current_user->roles) &&
		( in_array( 'administrator', $current_user->roles )
		|| in_array( 'web_administrator', $current_user->roles )
		|| ( ! defined( 'DOING_AJAX' ) && ! 'DOING_AJAX') ) ){
			return true;
		}else{
			return false;
		}
	}

	
	// format the phone numbers to be uniform through the site
	function pk_format_phone($phone, $extension = '', $formats = array()) {
		$formats = wp_parse_args($formats, array('format-7'=>'$1-$2',
															  'format-10'=>"($1) $2-$3",
															  'format-11'=>"$1 ($2) $3-$4"));
		$phone = preg_replace('/[^0-9]/','',$phone);
		$len = strlen($phone);
		if($len == 7) {
			$phone = preg_replace("/([0-9]{3})([0-9]{4})/", $formats['format-7'], $phone);
		} else if($len == 10) {
			$phone = preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", $formats['format-10'], $phone);
		} else if($len == 11) {
			$phone = preg_replace("/([0-9]{1})([0-9]{3})([0-9]{3})([0-9]{4})/", $formats['format-11'], $phone);
		}
		return $phone.($extension ? ' ext. '.$extension : '');
	}
	

	// register a custom post type: ex: pk_register_cpt(array('name' => 'FAQ', 'icon' => 'dashicons-format-status', 'position' => 5, 'is_singular' => true));
	function pk_register_cpt($cpt_array = array()){
		$default_cpt = array( 'name' => '', 
							'icon' => 'dashicons-admin-post', 
							'position' => 4, 
							'description' => '', 
							'is_singular' => false, 
							'exclude_from_search' => false,
							'supports' => array('title','editor','thumbnail','page-attributes'),
							'taxonomies' => array(),
							'has_archive' => false,
							'rewrite' => array(),
							'public' => true,
							'hierarchical' => false
						);	
		if(! empty($cpt_array)){
			$cpt_array = wp_parse_args( $cpt_array, $default_cpt );
			$slug = strtolower($cpt_array['name']);
			$plural = (substr($slug, -1) == 's') ? 'es' : 's';
			
			if(substr($slug, -1) == 'y' && ! $cpt_array['is_singular']){
				$slug = rtrim($slug, 'y');
				$plural = 'ies';
			}
			
			$plural_slug = ($cpt_array['is_singular']) ? $slug : $slug.$plural;
			$plural_slug = str_replace(" ", "-", $plural_slug);
			$label = ucwords(str_replace("-", " ", $cpt_array['name']));

			$is_y = false;
			if(substr($label, -1) == 'y' && ! $cpt_array['is_singular']){
				$label = rtrim($label, 'y');
				$is_y = true;
			}

			$plural_label = ($cpt_array['is_singular']) ? $label : $label.$plural;
			
			//we removed the y from the label to put on an ies...now let's add the Y back.
			if($is_y){
				$label .= 'y';
			}
			
			register_post_type( $plural_slug, array(
								'label' => $plural_label,
								'description' => $cpt_array['description'],
								'public' => $cpt_array['public'],
								'show_ui' => true,
								'show_in_menu' => true,
								'exclude_from_search' => $cpt_array['exclude_from_search'],
								'capability_type' => 'post',
								'map_meta_cap' => true,
								'hierarchical' => $cpt_array['hierarchical'],
								'has_archive' => $cpt_array['has_archive'],
								'rewrite' => $cpt_array['rewrite'],
								'query_var' => true,
								'taxonomies' => $cpt_array['taxonomies'],
								'menu_position' => $cpt_array['position'],
								'menu_icon' => $cpt_array['icon'],
								'supports' => $cpt_array['supports'],
								'labels' => array (
									'name' => $plural_label,
									'singular_name' => $label,
									'menu_name' => $plural_label,
									'add_new' => 'Add '.$label,
									'add_new_item' => 'Add New '.$label,
									'edit' => 'Edit',
									'edit_item' => 'Edit '.$label,
									'new_item' => 'New '.$label,
									'view' => 'View '.$plural_label,
									'view_item' => 'View '.$label,
									'search_items' => 'Search '.$plural_label,
									'not_found' => 'No '.$plural_label.' Found',
									'not_found_in_trash' => 'No '.$plural_label.' Found in Trash',
									'parent' => 'Parent '.$label
								)
							)); 
		}
	}


	/**
	 * Helper function for registering a taxonomy
	 *
	 * @param string $tax_name  	A url safe taxonomy name / slug
	 * @param string $post_type 	What post type this taxonomy will be applied to
	 * @param string $menu_title 	The title of the taxonomy. Human Readable.
	 * @param array  $rewrite 	 	Overwrite the rewrite
	 */
	function pk_register_taxonomy($tax_name = '', $post_type = '', $menu_title = '', $rewrite = array()){

		if(empty($rewrite)){
			$rewrite = array( 'slug' => $tax_name );
		}

		register_taxonomy(
			$tax_name,
			$post_type,
			array(
				'label' => __( $menu_title ),
				'rewrite' => $rewrite, 
				'capabilities' => array(
					'manage_terms' => 'manage_categories',
					'assign_terms' => 'manage_categories',
					'edit_terms' => 'manage_categories',
					'delete_terms' => 'manage_categories'
				),
				'hierarchical' => true
			)
		);
	}
	
	// get a pk-specific value
	function pk_get($key) {
		return pk_value($key);
	}
	
	// set a pk-specific value -- returns previous value
	function pk_set($key, $value=null) {
		return pk_value($key, $value);
	}
	
	// get/set a pk-specific value
	function pk_value() {
		static $list = array();
		
		// initialize value
		$value = null;
		
		// 1 argument = get, 2 arguments = set, anything else = invalid
		switch(func_num_args()) {
		
			// get value
			case 1:
				$key = func_get_arg(0);
				if(is_string($key)) {
					$value = (isset($list[$key]) ? $list[$key] : null);
				}
				break;
				
			// set value (and return old value)
			case 2:
				$key = func_get_arg(0);
				if(is_string($key)) {
					$value = (isset($list[$key]) ? $list[$key] : null);
					$list[$key] = func_get_arg(1);
				}
				break;
			
			// invalid usage
			default:
				break;
		}
		
		// return current/previous value
		return $value;
	}
	

	/*
	* Change "posts" name to "Blog Posts"
	*/
	add_action( 'admin_menu', 'pk_change_post_label' );
	function pk_change_post_label() {
		$new = apply_filters( 'pk_change_post_label', false);
		if(!$new){ return; }

	   global $menu;
	   global $submenu;
	   $menu[5][0] = $new.'s';
	   $submenu['edit.php'][5][0] = $new.'s';
	   $submenu['edit.php'][10][0] = 'Add '.$new;
	}


	/**
	 * Use WordPress' antispambot() to obfuscate the site's email, to protect from harvestor bots
	 *
	 * @param string $email The email address to obfuscate
	 * @param string $text 	The text to display to the user (for the mailto:email link)
	 * 
	 * @return string 		The obfuscated email address formatted as a link.
	 */
	function pk_antispam_email($email = "", $text = "" ){
		if (! filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
			if($text == ""){
				$text = antispambot($email);
			}
			$clean_link = '<a class="antispam" href="mailto:'.antispambot($email).'">'.$text.'</a>';
			
			return	$clean_link;
			
		}
	}


	/**
	 * Get the img path quickly
	 * 
	 * @return string the images directory
	 */
	function pk_img_path() {
		return get_stylesheet_directory_uri().'/images/';
	}

	/**
	 * Return the Video ID of a video based on the video URL
	 * @param  string $url the url of the YouTube video
	 * @return string      the video id
	 */
	function pk_get_youtube_id($url){
		if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match)) {
		    return $match[1];
		}

		return '';
	}

	//Allows a user to setup WordPress for HTML emails
	function set_html_content_type() {
		return 'text/html';
	}

	/**
	 * Detect if the provided url belongs to the same domain as our site OR if it is a PDF
	 */
	function pk_target_domain($url){
		if(strpos($url, get_home_url()) !== false && strpos($url, ".pdf") === false){
			return "";
		}else{
			return 'target="_blank" rel="noopener"';
		}
	}

	/**
	 * Pass in the PK button array
	 * @param  array $arr array containing all the PK button fields
	 * @return string      the correct URL for this type of button
	 */
	function pk_get_button_link($arr, $prefix = ''){

		$btn_link = '#';

		switch($arr[$prefix.'link_type']){
			case 'existing':

				$btn_link = !empty($arr[$prefix.'existing_link']) ? $arr[$prefix.'existing_link']->ID : false;
				$btn_link = $btn_link ? get_permalink($btn_link) : false;
				break;

			case 'manual':
				$btn_link = $arr[$prefix.'manual_url'];
				break;

			case 'file':
				$f = $arr[$prefix.'file'];
				$btn_link = $f ? $f['url'] : false;
				break;
		}

		return $btn_link;

	}

	/**
	 * Return a URL to Google Maps with the appropriate query string to a given location
	 *
	 * @param array $address 	the address of the location array('street_address' => '', 'city' => '', 'state' => '', 'zip' => '' )
	 * 
	 * @return string 			the google maps query string 	
	 */
	function pk_get_map_link($address){

		$q = urlencode(preg_replace('/\(.*\)/', '', $address['street_address'].', '.$address['city'].', '.$address['state'].' '.$address['zip']));

		return 'https://maps.google.com/?q='.$q;
	}
