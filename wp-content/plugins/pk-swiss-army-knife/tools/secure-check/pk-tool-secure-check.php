<?php
/**
 * STILL IN DEVELOPMENT!!!
 *
 * TODO: Need to convert this into a class.
 * TODO: Need to provide some kind of admin function to change the "days to delete" timer...but still have it be secure. Perhaps a max days check of 30 days.
 */

global $SECURED_MAGIC_WORD, $MAGIC_PASSWORD,$DAYS_TO_DELETE;

$SECURED_MAGIC_WORD = 'secured';
$MAGIC_PASSWORD = 'pksecretaccess';
$DAYS_TO_DELETE = 30;

//https://www.gravityhelp.com/documentation/article/gform_save_field_value/
add_filter( 'gform_save_field_value', 'pk_encrypt_value', 10, 4 );
function pk_encrypt_value( $value, $lead, $field, $form ) {

	if(! empty($value) && strpos($field->cssClass, 'secure') !== false){
		$encrypted = GFCommon::encrypt( $value );
		return $encrypted;
	}

	return $value;
}


//https://www.gravityhelp.com/documentation/article/gform_get_input_value/
add_filter( 'gform_get_input_value', 'pk_decrypt_value', 10, 4 );
function pk_decrypt_value( $value, $entry, $field, $input_id ) {
	global $SECURED_MAGIC_WORD;
	
	if(strpos($field->cssClass, 'secure') !== false){
		$pk_secure_access = get_transient( 'pk_secure_access' );
		$user = wp_get_current_user();
		if(! empty($user->ID) && $pk_secure_access == $user->ID){
			$decrypted = GFCommon::decrypt( $value );
			return $decrypted;
		}else{
			return $SECURED_MAGIC_WORD;
		}
	}

	return $value;
}


add_action('init', 'pk_check_secure_access');
function pk_check_secure_access(){
	$user = wp_get_current_user();
	if ( ! empty($user->ID) ) {
		$pk_secure_access = get_transient( 'pk_secure_access' );

		if($pk_secure_access == $user->ID){
			//set a transient for 1 hour. We will continue to keep this transient alive as long as the user is logged in...and reloads the page at least once an hour
			set_transient( 'pk_secure_access', $pk_secure_access, HOUR_IN_SECONDS );
		}
	}
}

//Clear the old secured data in the database to limit the length of the value's potential exposure.
function pk_clear_old_secure_data(){
	global $wpdb, $SECURED_MAGIC_WORD, $DAYS_TO_DELETE;

	if(empty($DAYS_TO_DELETE)){
		$DAYS_TO_DELETE = 14;
	}else if($DAYS_TO_DELETE > 29){
		$DAYS_TO_DELETE = 29;
	}

	$start_date = date( 'Y-m-d', strtotime('-30 days') );
	$end_date = date( 'Y-m-d', strtotime('-'.$DAYS_TO_DELETE.' days') );
	$search_criteria['start_date'] = $start_date;
	$search_criteria['end_date'] = $end_date;

	//get all entries older than 15 days
	$old_entries = GFAPI::get_entries( 0, $search_criteria );

	if(! empty($old_entries)){
		foreach($old_entries as $entry){
			foreach($entry as $fkey => $field){
				if($field === $SECURED_MAGIC_WORD){
					//set this secured field to empty.
					$result = GFAPI::update_entry_field( $entry['id'], $fkey, '' );
				}
			}
		}
	}
}


// START VOLUNTEER SETUP
add_action( 'admin_menu', 'register_secure_check_menu_page' );
function register_secure_check_menu_page(){
	$page_title = "Secure Check"; 
	$menu_title = "Secure Check";
	$capability = 'edit_posts'; //TODO pk: is this the best capability to use? 
	$menu_slug = 'secure-check'; 
	$function = 'secure_check'; 

	add_submenu_page( 'tools.php', $page_title, $menu_title, $capability, $menu_slug, $function );

	//Clear all the old secure data
	pk_clear_old_secure_data();
}


function secure_check(){
	global $wpdb, $MAGIC_PASSWORD;

	$secure_table = $wpdb->prefix.'secure_access';

	$wpdb->query("CREATE TABLE IF NOT EXISTS {$secure_table} (
	`secure_id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`user_id` int(11) NOT NULL,
	`secure_pin` varchar(100) NOT NULL,
	`valid_until` int(11) DEFAULT NULL,
	`used` int(11) NOT NULL DEFAULT '0'
	) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1");

	$pk_secure_access = get_transient( 'pk_secure_access' );

	$user = wp_get_current_user();
	$error = '';

	//TEST SECURE PIN
	if(! empty($_POST['secure-pin'])){
		$pin_query = "SELECT * FROM {$secure_table} WHERE user_id = {$user->ID} AND secure_pin = %s AND used = 0 AND valid_until > ".current_time( 'timestamp' );
		
		$is_secure = $wpdb->get_row($wpdb->prepare($pin_query, $_POST['secure-pin']));

		if(! empty($is_secure)){
			$wpdb->update(
				$secure_table,
				array(
					'used' => 1
				),
				array(
					'user_id' => $user->ID, 'secure_pin' => $_POST['secure-pin']
				)
			);

			$pk_secure_access = $user->ID;
			//set a transient for 1 hour. We will continue to keep this transient alive as long as the user is logged in...and reloads the page at least once an hour
			set_transient( 'pk_secure_access', $user->ID, HOUR_IN_SECONDS );

		}else{
			$error = 'We were unable to find that pin in our database.';
		}
	}

	//SEND SECURE KEY
	if(! empty($_POST['secure']) && ! empty($_POST['secret_password'])){

		if($_POST['secret_password'] != $MAGIC_PASSWORD){
			$error = 'Incorrect password';
		}else{

			$headers[] = 'From: '.get_bloginfo( 'name' ).' <'.get_option('admin_email').">";

			$subject = 'Secure Access';

			$pin = uniqid();

			$message = 'Your Secure Key: '.$pin."\r\n 

If you did not request this email your site may have become compromised. Please contact Powderkeg as soon as possible, support@powderkegwebdesign.com." ;

			$wpdb->insert( 
				$secure_table, 
				array( 
					'user_id' => $user->ID, 
					'secure_pin' => $pin,
					'valid_until' => current_time( 'timestamp' )+ (60 * 60) //1 hour from now
				), 
				array( 
					'%d', 
					'%s',
					'%s'
				) 
			);

			// send email
			wp_mail($user->data->user_email, $subject, $message, $headers);
		}
	}

	$pin_query = "SELECT * FROM {$secure_table} WHERE user_id = {$user->ID} AND used = 0 AND valid_until > ".current_time( 'timestamp' );

	$pin_results = $wpdb->get_results($pin_query);

	echo '<div class="wrap">
			<h2>Secure Check</h2>';

	if(! empty($error)){
		echo '<div class="notice notice-error is-dismissible">
				<p>'.$error.'</p>
			</div>';
	}

	if($pk_secure_access == $user->ID){

		echo '<div class="notice notice-success is-dismissible">
				<p><strong>You currently have secure access!</strong></p>
				</div>';

	}elseif(! empty($pin_results)){
		

		echo '<p><strong>Please enter your secure key below.</strong></p>';

		echo '<form action="" method="post">
				<input type="text" name="secure-pin" value="" />
				<button type="submit" class="button button-primary button-large" name="submit" >Submit</button>
			</form>';

	}else{

		echo '<p>When you request access you will be sent and email which contains a secret password. Please copy that password and paste it here within 30 minutes of requesting it.</p>';

		echo '<p>Once secure access is given, you will have secure access to volunteer information until you leave this site or close your browser.</p>';

		echo '<form action="" method="post">
				<input type="password" value="" name="secret_password" />
				<button type="submit" class="button button-primary button-large" name="secure" value="secure" >Request Secure Access</button>
			</form>';
	}	

	echo '</div>';

}

